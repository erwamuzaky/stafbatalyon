<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilePhotoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat profil staf supaya user punya tempat menyimpan foto.
     */
    private function profil(User $staf): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => Bidang::create(['nama' => 'Humas'])->id,
            'kelas' => 'XI RPL 1',
            'jabatan' => 'Anggota',
            'is_inti' => false,
        ]);
    }

    public function test_staf_dapat_memasang_foto(): void
    {
        Storage::fake('public');

        $staf = User::factory()->create();
        $this->profil($staf);
        $file = UploadedFile::fake()->image('foto.jpg', 300, 300);

        $this->actingAs($staf)
            ->patch(route('profile.photo.update'), ['foto' => $file])
            ->assertRedirect();

        $path = 'staf/'.$file->hashName();

        $this->assertSame($path, $staf->fresh()->stafProfile->foto);
        Storage::disk('public')->assertExists($path);
    }

    public function test_ganti_foto_menghapus_berkas_lama(): void
    {
        Storage::fake('public');

        $staf = User::factory()->create();
        $this->profil($staf);

        $lama = UploadedFile::fake()->image('lama.jpg', 300, 300);
        $pathLama = $lama->store('staf', 'public');
        $staf->stafProfile->update(['foto' => $pathLama]);

        $baru = UploadedFile::fake()->image('baru.jpg', 300, 300);
        $pathBaru = 'staf/'.$baru->hashName();

        $this->actingAs($staf)
            ->patch(route('profile.photo.update'), ['foto' => $baru])
            ->assertRedirect();

        $this->assertSame($pathBaru, $staf->fresh()->stafProfile->foto);
        Storage::disk('public')->assertExists($pathBaru);
        Storage::disk('public')->assertMissing($pathLama);
    }

    public function test_hapus_foto_mengosongkan_kolom_dan_berkas(): void
    {
        Storage::fake('public');

        $staf = User::factory()->create();
        $this->profil($staf);

        $foto = UploadedFile::fake()->image('foto.jpg', 300, 300);
        $path = $foto->store('staf', 'public');
        $staf->stafProfile->update(['foto' => $path]);

        $this->actingAs($staf)
            ->delete(route('profile.photo.destroy'))
            ->assertRedirect();

        $this->assertNull($staf->fresh()->stafProfile->foto);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_user_tanpa_profil_staf_ditolak(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('foto.jpg', 300, 300);

        $this->actingAs($admin)
            ->patch(route('profile.photo.update'), ['foto' => $file])
            ->assertForbidden();

        $this->assertDatabaseMissing('staf_profiles', ['user_id' => $admin->id]);
    }

    public function test_tamu_dialihkan_ke_halaman_masuk(): void
    {
        $this->patch(route('profile.photo.update'))->assertRedirect(route('login'));
        $this->delete(route('profile.photo.destroy'))->assertRedirect(route('login'));
    }

    public function test_foto_besar_dikecilkan_saat_diunggah(): void
    {
        Storage::fake('public');

        $staf = User::factory()->create();
        $this->profil($staf);
        $file = UploadedFile::fake()->image('besar.jpg', 1200, 1200);

        $this->actingAs($staf)
            ->patch(route('profile.photo.update'), ['foto' => $file])
            ->assertRedirect();

        $path = 'staf/'.$file->hashName();

        $this->assertSame($path, $staf->fresh()->stafProfile->foto);
        Storage::disk('public')->assertExists($path);

        $gambar = imagecreatefromstring(Storage::disk('public')->get($path));

        $this->assertNotFalse($gambar);
        $this->assertSame(800, imagesx($gambar));
        $this->assertSame(800, imagesy($gambar));
    }
}
