<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaganTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat profil staf beserta bidangnya.
     */
    private function profil(User $staf, ?Bidang $bidang = null, ?int $urutan = null): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
            'jabatan' => 'Anggota',
            'urutan' => $urutan,
        ]);
    }

    /**
     * Admin bisa membuka halaman atur bagan.
     */
    public function test_admin_bisa_membuka_halaman_bagan(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create(['name' => 'Rina Bagan']);
        $this->profil($staf);

        $response = $this->actingAs($admin)->get(route('admin.bagan.index'));

        $response->assertOk();
        $response->assertSee('Atur Bagan Struktur');
        $response->assertSee('Rina Bagan');
        $response->assertSee('Tidak ada atasan');
    }

    /**
     * Staf biasa tidak boleh membuka halaman bagan.
     */
    public function test_staf_tidak_bisa_membuka_halaman_bagan(): void
    {
        $staf = User::factory()->create();

        $this->actingAs($staf)->get(route('admin.bagan.index'))->assertForbidden();
        $this->actingAs($staf)
            ->put(route('admin.bagan.update'), ['atasan' => []])
            ->assertForbidden();
    }

    /**
     * Tamu diarahkan ke halaman masuk.
     */
    public function test_tamu_diarahkan_ke_halaman_masuk(): void
    {
        $this->get(route('admin.bagan.index'))->assertRedirect(route('login'));
    }

    /**
     * Admin menyimpan atasan, lalu bisa mengosongkannya lagi.
     */
    public function test_admin_bisa_menyimpan_atasan(): void
    {
        $admin = User::factory()->admin()->create();
        $atasan = User::factory()->create(['name' => 'Atasan Bagan']);
        $bawahan = User::factory()->create(['name' => 'Bawahan Bagan']);
        $profilAtasan = $this->profil($atasan);
        $profilBawahan = $this->profil($bawahan);

        $response = $this->actingAs($admin)->put(route('admin.bagan.update'), [
            'atasan' => [$profilBawahan->id => $profilAtasan->id],
        ]);

        $response->assertRedirect(route('admin.bagan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('staf_profiles', [
            'id' => $profilBawahan->id,
            'atasan_id' => $profilAtasan->id,
        ]);

        // Kirim kosong berarti atasan dilepas.
        $this->actingAs($admin)->put(route('admin.bagan.update'), [
            'atasan' => [$profilBawahan->id => ''],
        ])->assertRedirect(route('admin.bagan.index'));

        $this->assertDatabaseHas('staf_profiles', [
            'id' => $profilBawahan->id,
            'atasan_id' => null,
        ]);
    }

    /**
     * Staf tidak boleh dijadikan atasan dirinya sendiri.
     */
    public function test_menolak_atasan_diri_sendiri(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create();
        $profil = $this->profil($staf);

        $response = $this->actingAs($admin)->put(route('admin.bagan.update'), [
            'atasan' => [$profil->id => $profil->id],
        ]);

        $response->assertSessionHasErrors("atasan.{$profil->id}");
        $this->assertDatabaseHas('staf_profiles', [
            'id' => $profil->id,
            'atasan_id' => null,
        ]);
    }

    /**
     * Lingkaran ditolak: A atasan B, lalu B tidak boleh meng atasi A.
     */
    public function test_menolak_lingkaran(): void
    {
        $admin = User::factory()->admin()->create();
        $a = User::factory()->create();
        $b = User::factory()->create();
        $profilA = $this->profil($a);
        $profilB = $this->profil($b);

        // A punya atasan B.
        $this->actingAs($admin)->put(route('admin.bagan.update'), [
            'atasan' => [$profilA->id => $profilB->id],
        ])->assertRedirect(route('admin.bagan.index'));

        $this->assertDatabaseHas('staf_profiles', [
            'id' => $profilA->id,
            'atasan_id' => $profilB->id,
        ]);

        // B dijadikan meng atasi A: bikin lingkaran, harus ditolak.
        $response = $this->actingAs($admin)->put(route('admin.bagan.update'), [
            'atasan' => [$profilB->id => $profilA->id],
        ]);

        $response->assertSessionHasErrors("atasan.{$profilB->id}");
        $this->assertDatabaseHas('staf_profiles', [
            'id' => $profilB->id,
            'atasan_id' => null,
        ]);
    }

    /**
     * Menolak key profil yang tidak dikenal.
     */
    public function test_menolak_profil_tidak_dikenal(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create();
        $profil = $this->profil($staf);

        $response = $this->actingAs($admin)->put(route('admin.bagan.update'), [
            'atasan' => [999999 => $profil->id],
        ]);

        $response->assertSessionHasErrors('atasan');
    }

    /**
     * Staf yang belum disetujui tidak muncul di halaman bagan.
     */
    public function test_staf_belum_disetujui_tidak_muncul(): void
    {
        $admin = User::factory()->admin()->create();
        $approved = User::factory()->create(['name' => 'Staf Disetujui']);
        $pending = User::factory()->pending()->create(['name' => 'Staf Menunggu']);
        $this->profil($approved);
        $this->profil($pending);

        $response = $this->actingAs($admin)->get(route('admin.bagan.index'));

        $response->assertOk();
        $response->assertSee('Staf Disetujui');
        $response->assertDontSee('Staf Menunggu');
    }
}
