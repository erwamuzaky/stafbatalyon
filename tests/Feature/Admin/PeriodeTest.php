<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeriodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat periode dengan nilai bawaan yang bisa ditimpa.
     *
     * @param  array<string, mixed>  $overrides
     */
    private function periode(array $overrides = []): Periode
    {
        return Periode::create(array_merge([
            'nama' => 'Kepengurusan 2026/2027',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => null,
            'status' => 'aktif',
            'catatan' => null,
        ], $overrides));
    }

    /**
     * Admin bisa membuka daftar periode.
     */
    public function test_admin_bisa_melihat_daftar_periode(): void
    {
        $admin = User::factory()->admin()->create();
        $this->periode(['nama' => 'Kepengurusan 2025/2026']);

        $response = $this->actingAs($admin)->get(route('admin.periode.index'));

        $response->assertOk();
        $response->assertSee('Periode Kepengurusan');
        $response->assertSee('Kepengurusan 2025/2026');
    }

    /**
     * Admin bisa menambah periode baru.
     */
    public function test_admin_bisa_menambah_periode(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.periode.store'), [
            'nama' => 'Kepengurusan 2026/2027',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '',
            'status' => 'aktif',
            'catatan' => 'Awal kepengurusan baru.',
        ]);

        $response->assertRedirect(route('admin.periode.index'));
        $this->assertDatabaseHas('periodes', [
            'nama' => 'Kepengurusan 2026/2027',
            'tahun_mulai' => 2026,
            'status' => 'aktif',
        ]);
    }

    /**
     * Mengaktifkan periode baru otomatis menonaktifkan periode aktif sebelumnya.
     */
    public function test_mengaktifkan_periode_baru_menonaktifkan_yang_lama(): void
    {
        $admin = User::factory()->admin()->create();
        $lama = $this->periode(['nama' => 'Lama', 'status' => 'aktif']);

        $this->actingAs($admin)->post(route('admin.periode.store'), [
            'nama' => 'Baru',
            'tahun_mulai' => 2027,
            'tahun_selesai' => 2028,
            'tanggal_mulai' => '2027-07-01',
            'status' => 'aktif',
        ])->assertRedirect(route('admin.periode.index'));

        $this->assertSame('selesai', $lama->fresh()->status);
        $this->assertSame(1, Periode::where('status', 'aktif')->count());
    }

    /**
     * Admin bisa mengubah periode.
     */
    public function test_admin_bisa_mengubah_periode(): void
    {
        $admin = User::factory()->admin()->create();
        $periode = $this->periode(['nama' => 'Nama Lama']);

        $this->actingAs($admin)->patch(route('admin.periode.update', $periode), [
            'nama' => 'Nama Baru',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'tanggal_mulai' => '2026-07-01',
            'status' => 'selesai',
        ])->assertRedirect(route('admin.periode.index'));

        $this->assertSame('Nama Baru', $periode->fresh()->nama);
        $this->assertSame('selesai', $periode->fresh()->status);
    }

    /**
     * Tahun selesai yang lebih awal dari tahun mulai ditolak.
     */
    public function test_tahun_selesai_lebih_awal_ditolak(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.periode.store'), [
            'nama' => 'Salah',
            'tahun_mulai' => 2027,
            'tahun_selesai' => 2026,
            'tanggal_mulai' => '2026-07-01',
            'status' => 'aktif',
        ])->assertSessionHasErrors('tahun_selesai');

        $this->assertDatabaseMissing('periodes', ['nama' => 'Salah']);
    }

    /**
     * Periode yang masih punya kegiatan tidak boleh dihapus.
     */
    public function test_periode_dengan_kegiatan_tidak_bisa_dihapus(): void
    {
        $admin = User::factory()->admin()->create();
        $periode = $this->periode();

        Event::create([
            'nama' => 'Latihan Dasar',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-08-01',
            'status' => 'rencana',
            'periode_id' => $periode->id,
        ]);

        $this->actingAs($admin)->delete(route('admin.periode.destroy', $periode))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('periodes', ['id' => $periode->id]);
    }

    /**
     * Periode tanpa kaitan apa pun bisa dihapus.
     */
    public function test_periode_tanpa_kaitan_bisa_dihapus(): void
    {
        $admin = User::factory()->admin()->create();
        $periode = $this->periode();

        $this->actingAs($admin)->delete(route('admin.periode.destroy', $periode))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('periodes', ['id' => $periode->id]);
    }

    /**
     * Staf biasa tidak boleh mengakses halaman periode.
     */
    public function test_staf_tidak_bisa_mengakses_periode(): void
    {
        $staf = User::factory()->create();

        $this->actingAs($staf)->get(route('admin.periode.index'))->assertForbidden();
        $this->actingAs($staf)->get(route('admin.periode.create'))->assertForbidden();
        $this->actingAs($staf)->post(route('admin.periode.store'), [])->assertForbidden();
    }
}
