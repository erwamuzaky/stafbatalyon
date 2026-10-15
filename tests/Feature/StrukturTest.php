<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StrukturTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat profil staf pada satu bidang.
     */
    private function profil(User $staf, ?Bidang $bidang, ?string $jabatan = null, bool $inti = false, ?int $urutan = null): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
            'jabatan' => $jabatan,
            'is_inti' => $inti,
            'urutan' => $urutan,
            'no_hp' => '081234567890',
        ]);
    }

    /**
     * Hitung berapa kali satu nama staf tampil sebagai nama yang terlihat.
     *
     * Nama staf sebenarnya muncul dua kali per simpul: sekali di teks
     * terlihat (<p>...</p>) dan sekali lagi di span sr-only untuk pembaca
     * layar. Yang dihitung di sini cuma kemunculan teks terlihat, supaya
     * "tampil tepat sekali" benar-benar berarti satu simpul, bukan dua.
     */
    private function tampilTerlihat(string $html, string $nama): int
    {
        return substr_count($html, $nama.'</p>');
    }

    /**
     * Halaman struktur menampilkan foto staf jika tersedia.
     */
    public function test_halaman_struktur_menampilkan_foto_staf(): void
    {
        $bidang = Bidang::create(['nama' => 'Pimpinan']);

        $ketua = User::factory()->create(['name' => 'Ketua Berfoto']);
        $profil = $this->profil($ketua, $bidang, 'Ketua', true, 1);
        $profil->update(['foto' => 'staf/ketua.jpg']);

        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertSee(asset('storage/staf/ketua.jpg'), false);
    }

    /**
     * Halaman struktur menampilkan semua staf yang sudah disetujui.
     */
    public function test_halaman_struktur_menampilkan_semua_staf_disetujui(): void
    {
        $humas = Bidang::create(['nama' => 'Humas']);
        $sekretaris = Bidang::create(['nama' => 'Sekretaris']);

        $ketua = User::factory()->create(['name' => 'Ketua Batalyon']);
        $this->profil($ketua, $humas, 'Ketua', true, 1);

        $anggota = User::factory()->create(['name' => 'Anggota Humas']);
        $this->profil($anggota, $humas, 'Anggota');

        $sekretarisBatalyon = User::factory()->create(['name' => 'Sekretaris Batalyon']);
        $this->profil($sekretarisBatalyon, $sekretaris, 'Sekretaris', true, 2);

        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertSee('Struktur Lengkap');
        $response->assertSee('Ketua Batalyon');
        $response->assertSee('Anggota Humas');
        $response->assertSee('Sekretaris Batalyon');
    }

    /**
     * Halaman struktur tidak menampilkan staf yang belum atau ditolak.
     */
    public function test_halaman_struktur_tidak_menampilkan_staf_belum_disetujui(): void
    {
        $bidang = Bidang::create(['nama' => 'Humas']);

        $menunggu = User::factory()->pending()->create(['name' => 'Staf Menunggu']);
        $this->profil($menunggu, $bidang, 'Anggota');

        $ditolak = User::factory()->rejected()->create(['name' => 'Staf Ditolak']);
        $this->profil($ditolak, $bidang, 'Anggota');

        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertDontSee('Staf Menunggu');
        $response->assertDontSee('Staf Ditolak');
    }

    /**
     * Halaman struktur tidak memuat email dan nomor HP staf.
     */
    public function test_halaman_struktur_tidak_memuat_email_dan_nomor_hp(): void
    {
        $bidang = Bidang::create(['nama' => 'Sekretaris']);
        $staf = User::factory()->create([
            'name' => 'Rina Inti',
            'email' => 'rina.inti@contoh.test',
        ]);
        $this->profil($staf, $bidang, 'Sekretaris', true, 1);

        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertSee('Rina Inti');
        $response->assertDontSee('rina.inti@contoh.test');
        $response->assertDontSee('081234567890');
        $response->assertDontSee('no_hp');
    }

    /**
     * Urutan staf di halaman struktur mengikuti kolom "urutan".
     */
    public function test_urutan_staf_di_halaman_struktur_mengikuti_kolom_urutan(): void
    {
        $bidang = Bidang::create(['nama' => 'Pimpinan']);

        $komandanBatalyon = User::factory()->create(['name' => 'KOMANDAN BATALYON']);
        $this->profil($komandanBatalyon, $bidang, 'Komandan Batalyon', true, 1);

        $komandanPoltar = User::factory()->create(['name' => 'KOMANDAN POLTAR']);
        $this->profil($komandanPoltar, $bidang, 'Komandan Poltar', true, 2);

        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertSeeInOrder(['KOMANDAN BATALYON', 'KOMANDAN POLTAR']);
    }

    /**
     * Staf tanpa bidang tetap tampil sebagai simpul di bagan.
     */
    public function test_staf_tanpa_bidang_tetap_tampil_di_bagan(): void
    {
        $tanpaBidang = User::factory()->create(['name' => 'Staf Lepas']);
        $this->profil($tanpaBidang, null, 'Anggota');

        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertSee('Staf Lepas');
        $response->assertDontSee('Tanpa Bidang');
    }

    /**
     * Halaman struktur kosong menampilkan pesan belum ada data.
     */
    public function test_halaman_struktur_kosong_menampilkan_pesan(): void
    {
        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertSee('Belum ada data staf');
    }

    /**
     * Staf yang punya atasan tampil bersarang: atasan dulu, baru bawahannya.
     */
    public function test_bawahan_tampil_bersarang_di_bawah_atasan(): void
    {
        $atasan = User::factory()->create(['name' => 'Komandan Batalyon']);
        $profilAtasan = $this->profil($atasan, null, 'Komandan Batalyon', true, 1);

        $bawahan = User::factory()->create(['name' => 'Anggota Gerbang']);
        $profilBawahan = $this->profil($bawahan, null, 'Anggota', false, 2);
        $profilBawahan->update(['atasan_id' => $profilAtasan->id]);

        $response = $this->get(route('struktur'));

        $response->assertOk();
        // Urutan depth-first: simpul atasan muncul lebih dulu daripada bawahannya.
        $response->assertSeeInOrder(['Komandan Batalyon', 'Anggota Gerbang']);
        $response->assertSee('>Komandan Batalyon</p>', false);
        $response->assertSee('>Anggota Gerbang</p>', false);
    }

    /**
     * Staf yang atasannya tidak ikut tampil tetap muncul sebagai simpul teratas.
     */
    public function test_staf_dengan_atasan_tidak_tampil_tetap_jadi_simpul_teratas(): void
    {
        // Atasan yang user-nya masih pending: profilnya tidak ikut tampil,
        // jadi staf ini tidak boleh hilang dan naik jadi simpul teratas.
        $atasanPending = User::factory()->pending()->create(['name' => 'Atasan Belum Disetujui']);
        $profilPending = $this->profil($atasanPending, null, 'Ketua');

        $staf = User::factory()->create(['name' => 'Staf Menempel']);
        $profilStaf = $this->profil($staf, null, 'Anggota');
        $profilStaf->update(['atasan_id' => $profilPending->id]);

        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertSee('Staf Menempel');
        $response->assertDontSee('Atasan Belum Disetujui');
    }

    /**
     * Data atasan yang lingkar sendiri tidak membuat halaman error dan
     * kedua staf tetap tampil masing-masing sekali.
     */
    public function test_data_atasan_lingkar_tidak_membuat_halaman_error(): void
    {
        $stafA = User::factory()->create(['name' => 'Staf Lingkar Satu']);
        $profilA = $this->profil($stafA, null, 'Anggota');

        $stafB = User::factory()->create(['name' => 'Staf Lingkar Dua']);
        $profilB = $this->profil($stafB, null, 'Anggota');

        // Dibuat lingkar langsung lewat model supaya tidak diblokir validasi admin.
        $profilA->update(['atasan_id' => $profilB->id]);
        $profilB->update(['atasan_id' => $profilA->id]);

        $response = $this->get(route('struktur'));
        $html = $response->getContent();

        $response->assertOk();
        $this->assertSame(1, $this->tampilTerlihat($html, 'Staf Lingkar Satu'), 'Staf lingkar satu harus tampil tepat sekali.');
        $this->assertSame(1, $this->tampilTerlihat($html, 'Staf Lingkar Dua'), 'Staf lingkar dua harus tampil tepat sekali.');
    }

    /**
     * Setiap staf tampil tepat satu kali: tidak ada duplikat dan tidak ada yang hilang.
     */
    public function test_setiap_staf_tampil_tepat_satu_kali(): void
    {
        $atasan = User::factory()->create(['name' => 'Pemimpin Bagan']);
        $profilAtasan = $this->profil($atasan, null, 'Komandan', true, 1);

        $bawahan = User::factory()->create(['name' => 'Bawahan Bagan']);
        $profilBawahan = $this->profil($bawahan, null, 'Anggota', false, 2);
        $profilBawahan->update(['atasan_id' => $profilAtasan->id]);

        $atasanPending = User::factory()->pending()->create(['name' => 'Atasan Tidak Tampil']);
        $profilPending = $this->profil($atasanPending, null, 'Ketua');

        $yatim = User::factory()->create(['name' => 'Yatim Bagan']);
        $profilYatim = $this->profil($yatim, null, 'Anggota');
        $profilYatim->update(['atasan_id' => $profilPending->id]);

        $tanpaBidang = User::factory()->create(['name' => 'Lepas Bagan']);
        $this->profil($tanpaBidang, null, 'Anggota');

        $response = $this->get(route('struktur'));
        $html = $response->getContent();

        $response->assertOk();

        foreach (['Pemimpin Bagan', 'Bawahan Bagan', 'Yatim Bagan', 'Lepas Bagan'] as $nama) {
            $this->assertSame(1, $this->tampilTerlihat($html, $nama), "Staf {$nama} harus tampil tepat sekali.");
        }
    }

    /**
     * Halaman struktur menampilkan nama bidang tiap staf sebagai badge kecil.
     */
    public function test_halaman_struktur_menampilkan_nama_bidang_tiap_staf(): void
    {
        $humas = Bidang::create(['nama' => 'Humas']);

        $staf = User::factory()->create(['name' => 'Anggota Humas']);
        $this->profil($staf, $humas, 'Anggota', true, 1);

        $response = $this->get(route('struktur'));

        $response->assertOk();
        $response->assertSee('Humas');
    }
}
