<?php

namespace Tests\Feature\Admin;

use App\Models\Periode;
use App\Models\SerahTerima;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SerahTerimaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat periode sederhana untuk dipakai pada data serah terima.
     */
    private function periode(string $nama, string $status = 'selesai'): Periode
    {
        return Periode::create([
            'nama' => $nama,
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => null,
            'status' => $status,
            'catatan' => null,
        ]);
    }

    /**
     * Admin bisa membuka daftar serah terima.
     */
    public function test_admin_bisa_melihat_daftar_serah_terima(): void
    {
        $admin = User::factory()->admin()->create();
        $baru = $this->periode('Baru');

        SerahTerima::create([
            'periode_id' => $baru->id,
            'tanggal' => '2026-07-05',
            'tempat' => 'Ruang OSIS',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.serah-terima.index'));

        $response->assertOk();
        $response->assertSee('Serah Terima Kepengurusan');
        $response->assertSee('Baru');
        $response->assertSee('Ruang OSIS');
    }

    /**
     * Admin bisa mencatat serah terima.
     */
    public function test_admin_bisa_mencatat_serah_terima(): void
    {
        $admin = User::factory()->admin()->create();
        $lama = $this->periode('Lama');
        $baru = $this->periode('Baru');

        $this->actingAs($admin)->post(route('admin.serah-terima.store'), [
            'periode_id' => $baru->id,
            'periode_lama_id' => $lama->id,
            'tanggal' => '2026-07-05',
            'tempat' => 'Ruang OSIS',
            'catatan' => 'Penyerahan berkas dan inventaris.',
        ])->assertRedirect(route('admin.serah-terima.index'));

        $this->assertDatabaseHas('serah_terima', [
            'periode_id' => $baru->id,
            'periode_lama_id' => $lama->id,
            'tempat' => 'Ruang OSIS',
        ]);
    }

    /**
     * Berkas berita acara bisa diunggah dan disimpan.
     */
    public function test_berkas_berita_acara_bisa_diunggah(): void
    {
        $disk = Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $baru = $this->periode('Baru');

        $this->actingAs($admin)->post(route('admin.serah-terima.store'), [
            'periode_id' => $baru->id,
            'tanggal' => '2026-07-05',
            'berkas' => UploadedFile::fake()->create('berita-acara.pdf', 200, 'application/pdf'),
        ])->assertRedirect(route('admin.serah-terima.index'));

        $serahTerima = SerahTerima::firstOrFail();
        $this->assertNotNull($serahTerima->file_path);
        $disk->assertExists($serahTerima->file_path);
    }

    /**
     * Periode lama tidak boleh sama dengan periode baru.
     */
    public function test_periode_lama_tidak_boleh_sama_dengan_periode_baru(): void
    {
        $admin = User::factory()->admin()->create();
        $periode = $this->periode('Sama');

        $this->actingAs($admin)->post(route('admin.serah-terima.store'), [
            'periode_id' => $periode->id,
            'periode_lama_id' => $periode->id,
            'tanggal' => '2026-07-05',
        ])->assertSessionHasErrors('periode_lama_id');

        $this->assertDatabaseCount('serah_terima', 0);
    }

    /**
     * Admin bisa mengubah serah terima.
     */
    public function test_admin_bisa_mengubah_serah_terima(): void
    {
        $admin = User::factory()->admin()->create();
        $baru = $this->periode('Baru');

        $serahTerima = SerahTerima::create([
            'periode_id' => $baru->id,
            'tanggal' => '2026-07-05',
            'tempat' => 'Tempat Lama',
        ]);

        $this->actingAs($admin)->patch(route('admin.serah-terima.update', $serahTerima), [
            'periode_id' => $baru->id,
            'tanggal' => '2026-07-06',
            'tempat' => 'Tempat Baru',
        ])->assertRedirect(route('admin.serah-terima.index'));

        $this->assertSame('Tempat Baru', $serahTerima->fresh()->tempat);
    }

    /**
     * Admin bisa menghapus serah terima.
     */
    public function test_admin_bisa_menghapus_serah_terima(): void
    {
        $admin = User::factory()->admin()->create();
        $baru = $this->periode('Baru');

        $serahTerima = SerahTerima::create([
            'periode_id' => $baru->id,
            'tanggal' => '2026-07-05',
        ]);

        $this->actingAs($admin)->delete(route('admin.serah-terima.destroy', $serahTerima))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('serah_terima', 0);
    }

    /**
     * Staf biasa tidak boleh mengakses halaman serah terima.
     */
    public function test_staf_tidak_bisa_mengakses_serah_terima(): void
    {
        $staf = User::factory()->create();

        $this->actingAs($staf)->get(route('admin.serah-terima.index'))->assertForbidden();
        $this->actingAs($staf)->get(route('admin.serah-terima.create'))->assertForbidden();
        $this->actingAs($staf)->post(route('admin.serah-terima.store'), [])->assertForbidden();
    }
}
