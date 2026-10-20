<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StafFotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_mengunggah_foto_saat_menambah_staf(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);
        $file = UploadedFile::fake()->image('foto.jpg', 400, 400);

        $response = $this->actingAs($admin)->post(route('admin.staf.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'bidang_id' => $bidang->id,
            'kelas' => 'XI RPL 1',
            'foto' => $file,
        ]);

        $response->assertRedirect(route('admin.staf.index'));

        $staf = User::where('email', 'budi@example.test')->firstOrFail();
        $path = 'staf/'.$file->hashName();

        $this->assertSame($path, $staf->stafProfile->foto);
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_dapat_mengganti_foto_staf(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);

        $fotoLama = UploadedFile::fake()->image('lama.jpg', 300, 300);
        $pathLama = $fotoLama->store('staf', 'public');

        $staf = User::factory()->create();
        $staf->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'kelas' => 'XI RPL 1',
            'is_inti' => false,
            'foto' => $pathLama,
        ]);

        $fotoBaru = UploadedFile::fake()->image('baru.jpg', 300, 300);
        $pathBaru = 'staf/'.$fotoBaru->hashName();

        $response = $this->actingAs($admin)->patch(route('admin.staf.update', $staf), [
            'bidang_id' => $bidang->id,
            'kelas' => 'XI RPL 1',
            'is_inti' => '0',
            'foto' => $fotoBaru,
        ]);

        $response->assertRedirect(route('admin.staf.index'));

        $this->assertSame($pathBaru, $staf->fresh()->stafProfile->foto);
        Storage::disk('public')->assertExists($pathBaru);
        Storage::disk('public')->assertMissing($pathLama);
    }

    public function test_admin_dapat_menghapus_foto_staf(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);

        $foto = UploadedFile::fake()->image('foto.jpg', 300, 300);
        $path = $foto->store('staf', 'public');

        $staf = User::factory()->create();
        $staf->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'kelas' => 'XI RPL 1',
            'is_inti' => false,
            'foto' => $path,
        ]);

        $this->actingAs($admin)->get(route('admin.staf.edit', $staf))
            ->assertOk()
            ->assertSee('Hapus foto')
            ->assertDontSee('Hapus foto saat ini');

        $response = $this->actingAs($admin)->patch(route('admin.staf.update', $staf), [
            'bidang_id' => $bidang->id,
            'kelas' => 'XI RPL 1',
            'is_inti' => '0',
            'hapus_foto' => '1',
        ]);

        $response->assertRedirect(route('admin.staf.index'));

        $this->assertNull($staf->fresh()->stafProfile->foto);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_update_tanpa_ganti_foto_menjaga_foto_lama(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);

        $foto = UploadedFile::fake()->image('foto.jpg', 300, 300);
        $path = $foto->store('staf', 'public');

        $staf = User::factory()->create();
        $staf->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'kelas' => 'XI RPL 1',
            'is_inti' => false,
            'foto' => $path,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.staf.update', $staf), [
            'bidang_id' => $bidang->id,
            'kelas' => 'XII TKJ 2',
            'is_inti' => '0',
        ]);

        $response->assertRedirect(route('admin.staf.index'));

        $profil = $staf->fresh()->stafProfile;
        $this->assertSame($path, $profil->foto);
        $this->assertSame('XII TKJ 2', $profil->kelas);
        Storage::disk('public')->assertExists($path);
    }

    public function test_foto_bukan_gambar_ditolak(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);
        $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin)->post(route('admin.staf.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'bidang_id' => $bidang->id,
            'kelas' => 'XI RPL 1',
            'foto' => $file,
        ]);

        $response->assertSessionHasErrors('foto');
        $this->assertDatabaseMissing('users', ['email' => 'budi@example.test']);
    }
}
