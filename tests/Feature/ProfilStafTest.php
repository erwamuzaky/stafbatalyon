<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilStafTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat profil staf pada satu bidang.
     */
    private function profil(User $staf, ?Bidang $bidang = null): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
            'jabatan' => 'Anggota',
            'is_inti' => false,
            'no_hp' => '081200000000',
        ]);
    }

    /**
     * Halaman profil staf memuat field nomor HP.
     */
    public function test_halaman_profil_staf_menampilkan_field_nomor_hp(): void
    {
        $bidang = Bidang::create(['nama' => 'Humas']);
        $staf = User::factory()->create(['name' => 'Sari Profil']);
        $this->profil($staf, $bidang);

        $response = $this->actingAs($staf)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Nomor HP');
        $response->assertSee('081200000000');
    }

    /**
     * Staf bisa memperbarui nomor HP lewat halaman profil.
     */
    public function test_staf_dapat_memperbarui_nomor_hp(): void
    {
        $staf = User::factory()->create();
        $this->profil($staf);

        $response = $this->actingAs($staf)->patch(route('profile.update'), [
            'name' => 'Nama Baru',
            'email' => $staf->email,
            'no_hp' => '081298765432',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('staf_profiles', [
            'user_id' => $staf->id,
            'no_hp' => '081298765432',
        ]);

        $this->assertSame('Nama Baru', $staf->refresh()->name);
    }

    /**
     * Nomor HP yang terlalu panjang ditolak.
     */
    public function test_nomor_hp_terlalu_panjang_ditolak(): void
    {
        $staf = User::factory()->create();
        $this->profil($staf);

        $this->actingAs($staf)->patch(route('profile.update'), [
            'name' => 'Nama Baru',
            'email' => $staf->email,
            'no_hp' => str_repeat('8', 21),
        ])->assertSessionHasErrors('no_hp');
    }

    /**
     * Admin tanpa profil staf tetap bisa memperbarui profilnya.
     */
    public function test_admin_tanpa_profil_staf_tetap_bisa_memperbarui_profil(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('profile.edit'));
        $response->assertOk();

        $response = $this->actingAs($admin)->patch(route('profile.update'), [
            'name' => 'Admin Baru',
            'email' => $admin->email,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('profile.edit'));

        $this->assertSame('Admin Baru', $admin->refresh()->name);
        $this->assertDatabaseMissing('staf_profiles', ['user_id' => $admin->id]);
    }
}
