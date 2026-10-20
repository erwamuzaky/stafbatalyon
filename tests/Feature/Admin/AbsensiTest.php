<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat profil staf dengan bidang dan status inti tertentu.
     */
    private function profil(User $staf, ?Bidang $bidang = null, bool $inti = false, ?string $kelas = null): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
            'kelas' => $kelas,
            'jabatan' => $inti ? 'Koordinator' : 'Anggota',
            'is_inti' => $inti,
        ]);
    }

    /**
     * Isian cetak absensi yang sah.
     *
     * @return array<string, mixed>
     */
    private function isian(array $tambahan = []): array
    {
        return array_merge([
            'nama_kegiatan' => 'Latihan Dasar Kepemimpinan',
            'tanggal' => '2026-10-13',
            'filter' => 'semua',
            'baris_kosong' => 3,
        ], $tambahan);
    }

    /**
     * Halaman form cetak absensi bisa dibuka admin.
     */
    public function test_form_cetak_absensi_can_be_opened_by_admin(): void
    {
        $admin = User::factory()->admin()->create();
        Bidang::create(['nama' => 'Sekretaris']);

        $response = $this->actingAs($admin)->get(route('admin.absensi.create'));

        $response->assertOk();
        $response->assertSee('Cetak Absensi');
        $response->assertSee('Nama Kegiatan');
        $response->assertSee('Semua staf');
        $response->assertSee('Staf inti saja');
        $response->assertSee('Per bidang');
        $response->assertSee('Sekretaris');
        $response->assertSee('Tampilkan PDF');
        $response->assertSee('Unduh PDF');
    }

    /**
     * Hasil cetak absensi berupa berkas PDF.
     */
    public function test_cetak_absensi_returns_pdf(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create(['name' => 'Rina Absensi']);
        $this->profil($staf);

        $response = $this->actingAs($admin)->post(route('admin.absensi.cetak'), $this->isian());

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    /**
     * Tombol unduh menandai berkas PDF sebagai lampiran.
     */
    public function test_aksi_unduh_menghasilkan_berkas_lampiran(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create(['name' => 'Rina Absensi']);
        $this->profil($staf);

        $response = $this->actingAs($admin)->post(route('admin.absensi.cetak'), $this->isian([
            'aksi' => 'unduh',
            'penandatangan' => 'Rina Marlina',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString(
            'attachment',
            (string) $response->headers->get('content-disposition'),
        );
        $this->assertStringContainsString(
            'absensi-latihan-dasar-kepemimpinan-2026-10-13.pdf',
            (string) $response->headers->get('content-disposition'),
        );
    }

    /**
     * Penyaring "staf inti saja" hanya mengambil staf inti.
     */
    public function test_filter_inti_hanya_mengambil_staf_inti(): void
    {
        $admin = User::factory()->admin()->create();
        $inti = User::factory()->create(['name' => 'Ayu Inti']);
        $biasa = User::factory()->create(['name' => 'Budi Biasa']);
        $this->profil($inti, null, true);
        $this->profil($biasa);

        $peserta = User::pesertaAbsensi('inti');

        $this->assertCount(1, $peserta);
        $this->assertSame('Ayu Inti', $peserta->first()->name);

        // Halaman PDF tetap bisa dicetak dengan penyaring ini.
        $this->actingAs($admin)->post(route('admin.absensi.cetak'), $this->isian(['filter' => 'inti']))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Penyaring per bidang hanya mengambil staf pada bidang itu.
     */
    public function test_filter_per_bidang_hanya_mengambil_staf_bidang_itu(): void
    {
        $admin = User::factory()->admin()->create();
        $sekretaris = Bidang::create(['nama' => 'Sekretaris']);
        $humas = Bidang::create(['nama' => 'Humas']);

        $stafSekretaris = User::factory()->create(['name' => 'Sari Sekretaris']);
        $stafHumas = User::factory()->create(['name' => 'Hendra Humas']);
        $this->profil($stafSekretaris, $sekretaris);
        $this->profil($stafHumas, $humas);

        $peserta = User::pesertaAbsensi('bidang', $sekretaris->id);

        $this->assertCount(1, $peserta);
        $this->assertSame('Sari Sekretaris', $peserta->first()->name);
        $this->assertSame('Sekretaris', $peserta->first()->stafProfile->bidang->nama);

        $this->actingAs($admin)->post(route('admin.absensi.cetak'), $this->isian([
            'filter' => 'bidang',
            'bidang_id' => $sekretaris->id,
        ]))->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Penyaring per bidang wajib memilih bidang.
     */
    public function test_filter_per_bidang_wajib_memilih_bidang(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.absensi.cetak'), $this->isian([
            'filter' => 'bidang',
        ]))->assertSessionHasErrors('bidang_id');
    }

    /**
     * Akun pending dan rejected tidak ikut tercetak.
     */
    public function test_akun_pending_dan_rejected_tidak_ikut(): void
    {
        $admin = User::factory()->admin()->create();
        $approved = User::factory()->create(['name' => 'Staf Disetujui']);
        $pending = User::factory()->pending()->create(['name' => 'Staf Menunggu']);
        $rejected = User::factory()->rejected()->create(['name' => 'Staf Ditolak']);

        $this->profil($approved);
        $this->profil($pending);
        $this->profil($rejected);

        $peserta = User::pesertaAbsensi('semua');

        $this->assertCount(1, $peserta);
        $this->assertSame('Staf Disetujui', $peserta->first()->name);
    }

    /**
     * Peserta diurutkan berdasarkan nama.
     */
    public function test_peserta_diurutkan_berdasarkan_nama(): void
    {
        User::factory()->create(['name' => 'Zulaikha']);
        User::factory()->create(['name' => 'Ahmad']);
        $admin = User::factory()->admin()->create();

        $peserta = User::pesertaAbsensi('semua');

        $this->assertSame(['Ahmad', 'Zulaikha'], $peserta->pluck('name')->all());
        $this->assertTrue($admin->isAdmin());
    }

    /**
     * Jumlah baris kosong tidak boleh lebih dari 20.
     */
    public function test_baris_kosong_maksimal_20(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.absensi.cetak'), $this->isian([
            'baris_kosong' => 21,
        ]))->assertSessionHasErrors('baris_kosong');
    }

    /**
     * Template PDF memuat kop, tabel peserta, baris kosong, dan blok tanda tangan.
     */
    public function test_template_pdf_memuat_peserta_dan_tanda_tangan(): void
    {
        $bidang = Bidang::create(['nama' => 'Humas']);
        $staf = User::factory()->create(['name' => 'Rina Absensi']);
        $this->profil($staf, $bidang, false, 'XI RPL 3');

        $html = view('pdf.absensi', [
            'namaKegiatan' => 'Latihan Dasar Kepemimpinan',
            'tanggal' => now()->setDate(2026, 10, 13)->locale('id'),
            'keteranganPeserta' => 'Semua staf',
            'peserta' => User::pesertaAbsensi('semua'),
            'kelompokBidang' => User::pesertaAbsensi('semua')->groupBy(
                fn ($orang) => $orang->stafProfile?->bidang?->nama ?? 'Tanpa Bidang',
            ),
            'barisKosong' => 2,
            'penandatangan' => 'Rina Marlina',
            'jabatan' => 'Sekretaris Batalyon',
            'tempat' => 'Pariaman',
        ])->render();

        $this->assertStringContainsString('SMKN 3 Pariaman', $html);
        $this->assertStringContainsString('DAFTAR HADIR STAF BATALYON', $html);
        $this->assertStringContainsString('Latihan Dasar Kepemimpinan', $html);
        $this->assertStringContainsString('Selasa, 13 Oktober 2026', $html);
        $this->assertStringContainsString('Semua staf', $html);
        $this->assertStringContainsString('Rina Absensi', $html);
        $this->assertStringContainsString('Jabatan', $html);
        $this->assertStringContainsString('Anggota', $html);
        $this->assertStringContainsString('Humas', $html);
        $this->assertStringContainsString('Tanda Tangan', $html);
        $this->assertStringContainsString('Pariaman, 13 Oktober 2026', $html);
        $this->assertStringContainsString('Sekretaris Batalyon', $html);
        $this->assertStringContainsString('Rina Marlina', $html);

        // Dua baris kosong tambahan ikut tercetak, jadi ada nomor 1 sampai 3.
        $this->assertStringContainsString('>3</td>', $html);
    }

    /**
     * Staf biasa tidak boleh membuka halaman cetak absensi.
     */
    public function test_staf_tidak_bisa_membuka_halaman_absensi(): void
    {
        $staf = User::factory()->create();

        $this->actingAs($staf)->get('/admin/absensi')->assertForbidden();
        $this->actingAs($staf)->post(route('admin.absensi.cetak'), $this->isian())->assertForbidden();
        $this->actingAs($staf)->get(route('admin.dashboard'))->assertForbidden();
    }
}
