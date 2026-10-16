<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\Pengaturan;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat profil staf pada satu bidang.
     */
    private function profil(User $staf, ?Bidang $bidang, ?string $jabatan = null, bool $inti = false, ?string $kelas = null, ?int $urutan = null, ?StafProfile $atasan = null): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
            'kelas' => $kelas,
            'jabatan' => $jabatan,
            'is_inti' => $inti,
            'urutan' => $urutan,
            'atasan_id' => $atasan?->id,
            'no_hp' => '081234567890',
        ]);
    }

    /**
     * Halaman depan hanya menampilkan staf bidang "Pemimpin" yang sudah disetujui.
     */
    public function test_halaman_depan_hanya_menampilkan_staf_bidang_pemimpin(): void
    {
        $pemimpin = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);
        $humas = Bidang::create(['nama' => 'Humas']);

        $ketua = User::factory()->create(['name' => 'Ketua Batalyon']);
        $this->profil($ketua, $pemimpin, 'Ketua', true);

        $anggotaHumas = User::factory()->create(['name' => 'Anggota Biasa']);
        $this->profil($anggotaHumas, $humas, 'Anggota');

        $menunggu = User::factory()->pending()->create(['name' => 'Inti Menunggu']);
        $this->profil($menunggu, $pemimpin, 'Ketua', true);

        $ditolak = User::factory()->rejected()->create(['name' => 'Inti Ditolak']);
        $this->profil($ditolak, $pemimpin, 'Ketua', true);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Struktur Staf');
        $response->assertSee('Bidang');
        $response->assertSee('Staf Batalyon');
        $response->assertSee('SMKN 3 Pariaman');
        $response->assertSee('Ketua Batalyon');
        $response->assertSee('Ketua');

        $response->assertDontSee('Anggota Biasa');
        $response->assertDontSee('Inti Menunggu');
        $response->assertDontSee('Inti Ditolak');
    }

    /**
     * Halaman depan menampilkan kelas staf bidang "Pemimpin" yang sudah diisi.
     */
    public function test_halaman_depan_menampilkan_kelas_staf_inti(): void
    {
        $pemimpin = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);

        $ketua = User::factory()->create(['name' => 'Ketua Batalyon']);
        $this->profil($ketua, $pemimpin, 'Ketua', true, 'XI RPL 1');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Kelas XI RPL 1');
    }

    /**
     * Halaman depan tidak memuat email dan nomor HP staf.
     */
    public function test_halaman_depan_tidak_memuat_email_dan_nomor_hp(): void
    {
        $bidang = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);
        $staf = User::factory()->create([
            'name' => 'Rina Inti',
            'email' => 'rina.inti@contoh.test',
        ]);
        $this->profil($staf, $bidang, 'Sekretaris Batalyon', true);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Rina Inti');
        $response->assertDontSee('rina.inti@contoh.test');
        $response->assertDontSee('081234567890');
        $response->assertDontSee('no_hp');
    }

    /**
     * Halaman depan menampilkan daftar bidang dan tombol masuk/daftar.
     */
    public function test_halaman_depan_menampilkan_bidang_dan_tombol_masuk_daftar(): void
    {
        Bidang::create(['nama' => 'Perlengkapan']);
        Bidang::create(['nama' => 'Keamanan']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Perlengkapan');
        $response->assertSee('Keamanan');
        $response->assertSee(route('login'), false);
        $response->assertSee(route('register'), false);
    }

    /**
     * Halaman depan membaca teks kustom dari pengaturan.
     */
    public function test_halaman_depan_membaca_teks_dari_pengaturan(): void
    {
        Pengaturan::simpan(['hero_label' => 'Selamat Datang Di Portal']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Selamat Datang Di Portal');
    }

    /**
     * Halaman depan menampilkan foto staf bidang "Pemimpin" jika tersedia.
     */
    public function test_halaman_depan_menampilkan_foto_staf_inti(): void
    {
        $pemimpin = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);
        $ketua = User::factory()->create(['name' => 'Ketua Berfoto']);

        StafProfile::create([
            'user_id' => $ketua->id,
            'bidang_id' => $pemimpin->id,
            'jabatan' => 'Ketua',
            'is_inti' => true,
            'no_hp' => '081234567890',
            'foto' => 'staf-foto/ketua.jpg',
        ]);

        $urlFoto = Storage::disk('public')->url('staf-foto/ketua.jpg');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($urlFoto, false);
    }

    /**
     * Halaman depan menampilkan staf inti teratas sesuai "urutan".
     */
    public function test_halaman_depan_menampilkan_staf_inti_teratas_sesuai_urutan(): void
    {
        $bidang = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);

        $komandanBatalyon = User::factory()->create(['name' => 'KOMANDAN BATALYON']);
        $this->profil($komandanBatalyon, $bidang, 'Komandan Batalyon', true, null, 1);

        $komandanPoltar = User::factory()->create(['name' => 'KOMANDAN POLTAR']);
        $this->profil($komandanPoltar, $bidang, 'Komandan Poltar', true, null, 2);

        $komandanPoltir = User::factory()->create(['name' => 'KOMANDAN POLTIR']);
        $this->profil($komandanPoltir, $bidang, 'Komandan Poltir', true, null, 3);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('KOMANDAN BATALYON');
        $response->assertSee('KOMANDAN POLTAR');
        $response->assertSee('KOMANDAN POLTIR');
        $response->assertSeeInOrder(['KOMANDAN BATALYON', 'KOMANDAN POLTAR', 'KOMANDAN POLTIR']);
    }

    /**
     * Halaman depan menampilkan bawahan tepat di bawah atasannya.
     */
    public function test_halaman_depan_menampilkan_bawahan_di_bawah_atasannya(): void
    {
        $bidang = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);

        $atasan = User::factory()->create(['name' => 'ATASAN INTI']);
        $profilAtasan = $this->profil($atasan, $bidang, 'Komandan', true, null, 1);

        $bawahan = User::factory()->create(['name' => 'BAWAHAN BIASA']);
        $this->profil($bawahan, $bidang, 'Anggota', false, null, null, $profilAtasan);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ATASAN INTI');
        $response->assertSee('BAWAHAN BIASA');
        $response->assertSeeInOrder(['ATASAN INTI', 'BAWAHAN BIASA']);
    }

    /**
     * Halaman depan membatasi bagan ringkas sampai tiga tingkat saja.
     */
    public function test_halaman_depan_membatasi_bagan_sampai_tiga_tingkat(): void
    {
        $bidang = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);

        $a = User::factory()->create(['name' => 'TINGKAT A']);
        $profilA = $this->profil($a, $bidang, 'Pimpinan A', true, null, 1);

        $b = User::factory()->create(['name' => 'TINGKAT B']);
        $profilB = $this->profil($b, $bidang, 'Pimpinan B', false, null, null, $profilA);

        $c = User::factory()->create(['name' => 'TINGKAT C']);
        $profilC = $this->profil($c, $bidang, 'Pimpinan C', false, null, null, $profilB);

        $d = User::factory()->create(['name' => 'TINGKAT D']);
        $this->profil($d, $bidang, 'Pimpinan D', false, null, null, $profilC);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('TINGKAT A');
        $response->assertSee('TINGKAT B');
        $response->assertSee('TINGKAT C');
        $response->assertDontSee('TINGKAT D');
    }

    /**
     * Halaman depan tidak menampilkan staf bidang lain meski jadi bawahan staf "Pemimpin".
     */
    public function test_halaman_depan_tidak_menampilkan_staf_bidang_lain(): void
    {
        $pemimpin = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);
        $humas = Bidang::create(['nama' => 'Humas']);

        $atasan = User::factory()->create(['name' => 'ATASAN PEMIMPIN']);
        $profilAtasan = $this->profil($atasan, $pemimpin, 'Komandan', true, null, 1);

        $bawahanLain = User::factory()->create(['name' => 'BAWAHAN BIDANG HUMAS']);
        $this->profil($bawahanLain, $humas, 'Anggota', false, null, null, $profilAtasan);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ATASAN PEMIMPIN');
        $response->assertDontSee('BAWAHAN BIDANG HUMAS');
    }

    /**
     * Halaman depan menampilkan semua staf bidang "Pemimpin", termasuk yang non-inti tanpa atasan.
     */
    public function test_halaman_depan_menampilkan_semua_staf_bidang_pemimpin(): void
    {
        $pemimpin = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);

        $nonInti = User::factory()->create(['name' => 'PEMIMPIN NON INTI']);
        $this->profil($nonInti, $pemimpin, 'Anggota', false, null, 1);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('PEMIMPIN NON INTI');
    }

    /**
     * Halaman depan tidak menampilkan staf bidang yang penanda berandanya mati,
     * walau nama bidangnya "Pemimpin" dan stafnya sudah disetujui.
     */
    public function test_halaman_depan_tidak_menampilkan_bidang_tanpa_penanda(): void
    {
        $pemimpinTanpaPenanda = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => false]);

        $staf = User::factory()->create(['name' => 'STAF TANPA PENANDA']);
        $this->profil($staf, $pemimpinTanpaPenanda, 'Ketua', true, null, 1);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('STAF TANPA PENANDA');
    }

    /**
     * Halaman depan menampilkan tautan menuju halaman struktur lengkap.
     */
    public function test_halaman_depan_menampilkan_tautan_struktur_lengkap(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('struktur'), false);
        $response->assertSee('Lihat struktur lengkap');
    }

    /**
     * Satu foto latar dipakai bersama oleh bagian Struktur Staf dan Bidang.
     */
    public function test_halaman_depan_memakai_satu_latar_untuk_struktur_dan_bidang(): void
    {
        $pemimpin = Bidang::create(['nama' => 'Pemimpin', 'tampil_di_beranda' => true]);
        $ketua = User::factory()->create(['name' => 'Ketua Berfoto']);

        StafProfile::create([
            'user_id' => $ketua->id,
            'bidang_id' => $pemimpin->id,
            'jabatan' => 'Ketua',
            'is_inti' => true,
            'no_hp' => '081234567890',
        ]);

        Pengaturan::simpan(['bagian_background' => 'pengaturan/bg-bagian.jpg']);

        $response = $this->get('/');

        $response->assertOk();

        $konten = $response->getContent();

        // Satu lapisan foto untuk kedua bagian, jadi tidak ada sambungan yang bisa bergaris.
        $this->assertSame(1, substr_count($konten, asset('storage/pengaturan/bg-bagian.jpg')));
        $this->assertSame(1, substr_count($konten, 'background-image: url'));
        // Garis penghubung bagan dibuat terang supaya menyatu dengan latar gelap.
        $this->assertStringContainsString('bagan-terang', $konten);
    }

    /**
     * Tanpa foto latar, bagian tersebut kembali memakai latar warna biasa.
     */
    public function test_halaman_depan_tanpa_latar_kembali_ke_warna_dasar(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('background-image: url', false);
    }

    /**
     * Tingkat kegelapan lapisan gelap mengikuti pengaturan admin.
     */
    public function test_halaman_depan_memakai_kepekatan_samar_untuk_lapisan_latar(): void
    {
        Pengaturan::simpan([
            'bagian_background' => 'pengaturan/bg-bagian.jpg',
            'latar_kepekatan' => 'samar',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('via-laut-900/45', false);
        $response->assertDontSee('via-laut-900/55', false);
    }

    /**
     * Kalau key belum diisi, lapisan gelap memakai tingkat bawaan "sedang".
     */
    public function test_halaman_depan_memakai_kepekatan_sedang_saat_belum_diatur(): void
    {
        Pengaturan::simpan(['bagian_background' => 'pengaturan/bg-bagian.jpg']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('via-laut-900/55', false);
    }

    /**
     * Tanpa foto latar, lapisan gelap tidak dirender sama sekali.
     */
    public function test_halaman_depan_tanpa_latar_tidak_menampilkan_lapisan_gelap(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('bg-gradient-to-b from-laut-900/80', false);
    }
}
