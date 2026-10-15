<?php

namespace Tests\Feature\Auth;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        // Tanpa unggahan foto, kolom foto tetap kosong.
        $this->assertNull($user->stafProfile->foto);
    }

    public function test_pendaftaran_dengan_foto_disimpan(): void
    {
        Storage::fake('public');

        $bidang = Bidang::create(['nama' => 'Sekretaris']);
        $file = UploadedFile::fake()->image('foto.jpg', 300, 300);

        $response = $this->post('/register', [
            'name' => 'Calon Berfoto',
            'email' => 'berfoto@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'bidang_id' => $bidang->id,
            'kelas' => 'X RPL 1',
            'no_hp' => '081234567890',
            'foto' => $file,
        ]);

        $response->assertRedirect(route('registration.pending'));
        $this->assertGuest();

        $user = User::where('email', 'berfoto@example.com')->firstOrFail();
        $path = 'staf/'.$file->hashName();

        $this->assertSame($path, $user->stafProfile->foto);
        Storage::disk('public')->assertExists($path);
    }

    public function test_berkas_bukan_gambar_ditolak(): void
    {
        Storage::fake('public');

        $bidang = Bidang::create(['nama' => 'Sekretaris']);
        $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

        $response = $this->post('/register', [
            'name' => 'Calon Salah',
            'email' => 'salah@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'bidang_id' => $bidang->id,
            'kelas' => 'X RPL 1',
            'no_hp' => '081234567890',
            'foto' => $file,
        ]);

        $response->assertSessionHasErrors('foto');
        $this->assertNull(User::where('email', 'salah@example.com')->first());
    }

    public function test_foto_lebih_dari_dua_mb_ditolak(): void
    {
        Storage::fake('public');

        $bidang = Bidang::create(['nama' => 'Sekretaris']);
        $file = UploadedFile::fake()->image('besar.jpg')->size(2049);

        $response = $this->post('/register', [
            'name' => 'Calon Besar',
            'email' => 'besar@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'bidang_id' => $bidang->id,
            'kelas' => 'X RPL 1',
            'no_hp' => '081234567890',
            'foto' => $file,
        ]);

        $response->assertSessionHasErrors('foto');
        $this->assertNull(User::where('email', 'besar@example.com')->first());
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
