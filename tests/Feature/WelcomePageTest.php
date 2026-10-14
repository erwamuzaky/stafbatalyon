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
    private function profil(User $staf, ?Bidang $bidang, ?string $jabatan = null, bool $inti = false, ?string $kelas = null): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
            'kelas' => $kelas,
            'jabatan' => $jabatan,
            'is_inti' => $inti,
            'no_hp' => '081234567890',
        ]);
    }

    /**
     * Halaman depan hanya menampilkan staf inti yang sudah disetujui.
     */
    public function test_halaman_depan_hanya_menampilkan_staf_inti_disetujui(): void
    {
        $humas = Bidang::create(['nama' => 'Humas']);

        $ketua = User::factory()->create(['name' => 'Ketua Batalyon']);
        $this->profil($ketua, $humas, 'Ketua', true);

        $anggota = User::factory()->create(['name' => 'Anggota Biasa']);
        $this->profil($anggota, $humas, 'Anggota');

        $menunggu = User::factory()->pending()->create(['name' => 'Inti Menunggu']);
        $this->profil($menunggu, $humas, 'Ketua', true);

        $ditolak = User::factory()->rejected()->create(['name' => 'Inti Ditolak']);
        $this->profil($ditolak, $humas, 'Ketua', true);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Struktur Staf');
        $response->assertSee('Bidang');
        $response->assertSee('Staf Batalyon');
        $response->assertSee('SMKN 3 Pariaman');
        $response->assertSee('Ketua Batalyon');
        $response->assertSee('Ketua');
        $response->assertSee('Humas');

        $response->assertDontSee('Anggota Biasa');
        $response->assertDontSee('Inti Menunggu');
        $response->assertDontSee('Inti Ditolak');
    }

    /**
     * Halaman depan menampilkan kelas staf inti yang sudah diisi.
     */
    public function test_halaman_depan_menampilkan_kelas_staf_inti(): void
    {
        $humas = Bidang::create(['nama' => 'Humas']);

        $ketua = User::factory()->create(['name' => 'Ketua Batalyon']);
        $this->profil($ketua, $humas, 'Ketua', true, 'XI RPL 1');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Kelas XI RPL 1');
    }

    /**
     * Halaman depan tidak memuat email dan nomor HP staf.
     */
    public function test_halaman_depan_tidak_memuat_email_dan_nomor_hp(): void
    {
        $bidang = Bidang::create(['nama' => 'Sekretaris']);
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
     * Halaman depan menampilkan foto staf inti jika tersedia.
     */
    public function test_halaman_depan_menampilkan_foto_staf_inti(): void
    {
        $humas = Bidang::create(['nama' => 'Humas']);
        $ketua = User::factory()->create(['name' => 'Ketua Berfoto']);

        StafProfile::create([
            'user_id' => $ketua->id,
            'bidang_id' => $humas->id,
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
}
