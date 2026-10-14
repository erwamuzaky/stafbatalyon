<?php

namespace Tests\Feature\Auth;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_staf_is_created_as_pending_without_being_logged_in(): void
    {
        $bidang = Bidang::create(['nama' => 'Sekretaris']);

        $response = $this->post('/register', [
            'name' => 'Calon Staf',
            'email' => 'calon@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'bidang_id' => $bidang->id,
            'kelas' => 'X RPL 1',
            'jabatan' => 'Anggota',
            'no_hp' => '081234567890',
        ]);

        // Tidak boleh otomatis login.
        $this->assertGuest();
        $response->assertRedirect(route('registration.pending'));

        $user = User::where('email', 'calon@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('staf', $user->role);
        $this->assertSame('pending', $user->status);

        $this->assertNotNull($user->stafProfile);
        $this->assertSame($bidang->id, $user->stafProfile->bidang_id);
        $this->assertSame('X RPL 1', $user->stafProfile->kelas);
        $this->assertSame('Anggota', $user->stafProfile->jabatan);
        $this->assertSame('081234567890', $user->stafProfile->no_hp);
        $this->assertFalse($user->stafProfile->is_inti);
    }

    public function test_kelas_bidang_dan_no_hp_wajib_saat_daftar(): void
    {
        $response = $this->post('/register', [
            'name' => 'Calon Staf',
            'email' => 'calon2@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['bidang_id', 'kelas', 'no_hp']);
        $this->assertGuest();
        $this->assertNull(User::where('email', 'calon2@example.com')->first());
    }

    public function test_pending_page_can_be_rendered(): void
    {
        $response = $this->get(route('registration.pending'));

        $response->assertOk();
        $response->assertSee('Menunggu Persetujuan Admin');
    }
}
