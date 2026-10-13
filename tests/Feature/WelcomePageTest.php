<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat profil staf pada satu bidang.
     */
    private function profil(User $staf, ?Bidang $bidang, ?string $jabatan = null, bool $inti = false): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
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
}
