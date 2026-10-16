<?php

namespace Tests\Feature\Admin;

use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaturanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_melihat_halaman_persetujuan_pengaturan(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.pengaturan.index'));

        $response->assertOk();
        $response->assertSee('Pengaturan Halaman Depan');
    }

    public function test_pengaturan_admin_dapat_mengubah_nilai_teks(): void
    {
        $admin = User::factory()->admin()->create();

        $data = [
            'situs_nama' => 'Nama Situs Baru',
            'situs_sekolah' => 'Sekolah Baru',
            'hero_label' => 'Label Hero Baru',
            'tentang_judul' => 'Judul Tentang Baru',
            'tentang_pengantar' => 'Pengantar Tentang Baru',
            'tentang_fitur1_judul' => 'Fitur 1 Baru',
            'tentang_fitur1_teks' => 'Teks Fitur 1 Baru',
            'tentang_fitur2_judul' => 'Fitur 2 Baru',
            'tentang_fitur2_teks' => 'Teks Fitur 2 Baru',
            'tentang_fitur3_judul' => 'Fitur 3 Baru',
            'tentang_fitur3_teks' => 'Teks Fitur 3 Baru',
            'struktur_judul' => 'Judul Struktur Baru',
            'struktur_subjudul' => 'Subjudul Struktur Baru',
            'bidang_judul' => 'Judul Bidang Baru',
            'bidang_subjudul' => 'Subjudul Bidang Baru',
            'ajakan_judul' => 'Judul Ajakan Baru',
            'ajakan_teks' => 'Teks Ajakan Baru',
            'footer_nama' => 'Nama Footer Baru',
            'footer_sekolah' => 'Sekolah Footer Baru',
            'footer_peta_kata_kunci' => 'Kata Kunci Footer Baru',
            'footer_peta_catatan' => 'Catatan Footer Baru',
            'footer_hak_cipta' => 'Hak Cipta Footer Baru',
        ];

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), $data);

        $response->assertRedirect(route('admin.pengaturan.index'));
        $response->assertSessionHas('success', 'Pengaturan halaman depan sudah disimpan.');

        foreach ($data as $kunci => $nilai) {
            $this->assertDatabaseHas('pengaturan', [
                'kunci' => $kunci,
                'nilai' => $nilai,
            ]);
        }
    }

    public function test_admin_dapat_mengunggah_background_hero(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('hero-bg.jpg', 800, 600);

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'hero_background' => $file,
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));
        $response->assertSessionHas('success');

        // Verifikasi bahwa file tersimpan
        Storage::disk('public')->assertExists('pengaturan/'.$file->hashName());

        // Verifikasi bahwa path disimpan di DB
        $this->assertDatabaseHas('pengaturan', [
            'kunci' => 'hero_background',
            'nilai' => 'pengaturan/'.$file->hashName(),
        ]);
    }

    public function test_admin_dapat_mengganti_background_hero_yang_sudah_ada(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        // Background lama sudah tersimpan.
        $lama = UploadedFile::fake()->image('lama.jpg', 800, 600);
        $pathLama = $lama->store('pengaturan', 'public');
        Pengaturan::simpan(['hero_background' => $pathLama]);

        // Form mengirim hapus_background=0 saat checkbox tidak dicentang.
        $baru = UploadedFile::fake()->image('baru.jpg', 800, 600);
        $pathBaru = 'pengaturan/'.$baru->hashName();

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'hero_background' => $baru,
            'hapus_background' => '0',
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));

        // Background baru dipakai, background lama dibersihkan.
        $this->assertDatabaseHas('pengaturan', [
            'kunci' => 'hero_background',
            'nilai' => $pathBaru,
        ]);
        Storage::disk('public')->assertExists($pathBaru);
        Storage::disk('public')->assertMissing($pathLama);
    }

    public function test_admin_dapat_mengahapus_background_hero(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        // Simpan background dulu
        $file = UploadedFile::fake()->image('hero-bg.jpg', 800, 600);
        $path = $file->store('pengaturan', 'public');
        Pengaturan::simpan(['hero_background' => $path]);

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'hapus_background' => '1',
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));
        $response->assertSessionHas('success');

        // Verifikasi bahwa file dihapus
        Storage::disk('public')->assertMissing($path);

        // Verifikasi bahwa nilai hero_background kosong
        $this->assertDatabaseHas('pengaturan', [
            'kunci' => 'hero_background',
            'nilai' => '',
        ]);
    }

    public function test_staf_bukan_admin_tidak_boleh_mengakses(): void
    {
        $staf = User::factory()->create();

        $response = $this->actingAs($staf)->get(route('admin.pengaturan.index'));

        $response->assertForbidden();

        $response = $this->actingAs($staf)->patch(route('admin.pengaturan.update'), []);

        $response->assertForbidden();
    }

    public function test_tamu_tidak_login_dialihkan(): void
    {
        $response = $this->get(route('admin.pengaturan.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_hanya_mengubah_kunci_teks(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        $data = [
            'situs_nama' => 'Hanya Edit Teks',
            'hero_background' => UploadedFile::fake()->image('test.jpg'),
            'hapus_background' => '1',
        ];

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), $data);

        $response->assertRedirect(route('admin.pengaturan.index'));
        $response->assertSessionHas('success');

        // Verifikasi bahwa hanya kunci teks yang diubah
        $this->assertDatabaseHas('pengaturan', [
            'kunci' => 'situs_nama',
            'nilai' => 'Hanya Edit Teks',
        ]);

        // hero_background tetap kosong karena hapus_background true
        $this->assertDatabaseHas('pengaturan', [
            'kunci' => 'hero_background',
            'nilai' => '',
        ]);

        // File yang terlanjur diunggah bareng "hapus background" tidak boleh tertinggal.
        Storage::disk('public')->assertDirectoryEmpty('pengaturan');
    }

    public function test_admin_dapat_mengunggah_background_bagian(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        $gambar = UploadedFile::fake()->image('bg-bagian.jpg', 1600, 900);

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'bagian_background' => $gambar,
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));
        $response->assertSessionHas('success');

        $path = 'pengaturan/'.$gambar->hashName();

        Storage::disk('public')->assertExists($path);
        $this->assertDatabaseHas('pengaturan', ['kunci' => 'bagian_background', 'nilai' => $path]);
    }

    public function test_admin_dapat_mengganti_background_bagian_yang_sudah_ada(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        $lama = UploadedFile::fake()->image('lama.jpg', 800, 600);
        $pathLama = $lama->store('pengaturan', 'public');
        Pengaturan::simpan(['bagian_background' => $pathLama]);

        // Form mengirim hapus_background_bagian=0 saat checkbox tidak dicentang.
        $baru = UploadedFile::fake()->image('baru.jpg', 800, 600);
        $pathBaru = 'pengaturan/'.$baru->hashName();

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'bagian_background' => $baru,
            'hapus_background_bagian' => '0',
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));

        $this->assertDatabaseHas('pengaturan', ['kunci' => 'bagian_background', 'nilai' => $pathBaru]);
        Storage::disk('public')->assertExists($pathBaru);
        Storage::disk('public')->assertMissing($pathLama);
    }

    public function test_admin_dapat_mengahapus_background_bagian(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        $gambar = UploadedFile::fake()->image('bg-bagian.jpg', 800, 600);
        $path = $gambar->store('pengaturan', 'public');
        Pengaturan::simpan(['bagian_background' => $path]);

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'hapus_background_bagian' => '1',
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseHas('pengaturan', ['kunci' => 'bagian_background', 'nilai' => '']);
    }

    public function test_halaman_pengaturan_menampilkan_kolom_latar_bagian(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.pengaturan.index'));

        $response->assertOk();
        $response->assertSee('name="bagian_background"', false);
        $response->assertSee('Background Struktur Staf dan Bidang', false);
    }

    public function test_admin_dapat_mengunggah_background_struktur_lengkap(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        $gambar = UploadedFile::fake()->image('bg-struktur.jpg', 1600, 900);

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'struktur_lengkap_background' => $gambar,
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));
        $response->assertSessionHas('success');

        $path = 'pengaturan/'.$gambar->hashName();

        Storage::disk('public')->assertExists($path);
        $this->assertDatabaseHas('pengaturan', ['kunci' => 'struktur_lengkap_background', 'nilai' => $path]);
    }

    public function test_admin_dapat_mengganti_background_struktur_lengkap_yang_sudah_ada(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        $lama = UploadedFile::fake()->image('lama.jpg', 800, 600);
        $pathLama = $lama->store('pengaturan', 'public');
        Pengaturan::simpan(['struktur_lengkap_background' => $pathLama]);

        // Form mengirim hapus_background_struktur=0 saat checkbox tidak dicentang.
        $baru = UploadedFile::fake()->image('baru.jpg', 800, 600);
        $pathBaru = 'pengaturan/'.$baru->hashName();

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'struktur_lengkap_background' => $baru,
            'hapus_background_struktur' => '0',
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));

        $this->assertDatabaseHas('pengaturan', ['kunci' => 'struktur_lengkap_background', 'nilai' => $pathBaru]);
        Storage::disk('public')->assertExists($pathBaru);
        Storage::disk('public')->assertMissing($pathLama);
    }

    public function test_admin_dapat_mengahapus_background_struktur_lengkap(): void
    {
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        $gambar = UploadedFile::fake()->image('bg-struktur.jpg', 800, 600);
        $path = $gambar->store('pengaturan', 'public');
        Pengaturan::simpan(['struktur_lengkap_background' => $path]);

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'hapus_background_struktur' => '1',
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseHas('pengaturan', ['kunci' => 'struktur_lengkap_background', 'nilai' => '']);
    }

    public function test_halaman_pengaturan_menampilkan_kolom_latar_struktur_lengkap(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.pengaturan.index'));

        $response->assertOk();
        $response->assertSee('name="struktur_lengkap_background"', false);
        $response->assertSee('Background Halaman Struktur Lengkap', false);
    }

    public function test_admin_dapat_menyimpan_tingkat_kegelapan_latar(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'latar_kepekatan' => 'samar',
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pengaturan', ['kunci' => 'latar_kepekatan', 'nilai' => 'samar']);

        // Ganti ke pilihan lain, nilai lama harus tertimpa.
        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'latar_kepekatan' => 'tegas',
        ]);

        $response->assertRedirect(route('admin.pengaturan.index'));
        $this->assertDatabaseHas('pengaturan', ['kunci' => 'latar_kepekatan', 'nilai' => 'tegas']);
    }

    public function test_tingkat_kegelapan_latar_ngawur_ditolak(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->patch(route('admin.pengaturan.update'), [
            'latar_kepekatan' => 'sangat-gelap',
        ]);

        $response->assertSessionHasErrors([
            'latar_kepekatan' => 'Tingkat kegelapan latar harus salah satu dari: Samar, Sedang, atau Tegas.',
        ]);
        $this->assertDatabaseMissing('pengaturan', ['kunci' => 'latar_kepekatan', 'nilai' => 'sangat-gelap']);
    }

    public function test_halaman_pengaturan_menampilkan_pilihan_tingkat_kegelapan(): void
    {
        $admin = User::factory()->admin()->create();

        Pengaturan::simpan(['latar_kepekatan' => 'tegas']);

        $response = $this->actingAs($admin)->get(route('admin.pengaturan.index'));

        $response->assertOk();
        $response->assertSee('name="latar_kepekatan"', false);
        $response->assertSee('Tingkat kegelapan latar foto');
        // Pilihan yang sedang tersimpan harus terpilih.
        $response->assertSee('<option value="tegas" selected', false);
    }
}
