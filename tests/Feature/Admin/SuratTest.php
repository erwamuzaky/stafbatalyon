<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Periode;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratTest extends TestCase
{
    use RefreshDatabase;

    private function periode(): Periode
    {
        return Periode::create([
            'nama' => 'Kepengurusan 2026/2027',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'tanggal_mulai' => '2026-07-01',
            'status' => 'aktif',
        ]);
    }

    public function test_admin_bisa_melihat_daftar_surat(): void
    {
        $admin = User::factory()->admin()->create();
        Surat::create([
            'jenis' => 'sk',
            'nomor_urut' => 1,
            'nomor' => '001/SK/X/2026',
            'tanggal' => '2026-10-05',
            'perihal' => 'Surat Keputusan Anggota',
            'penandatangan' => 'Admin Staf Batalyon',
            'jabatan' => 'Ketua Batalyon',
            'tempat' => 'Pariaman',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.surat.index'));

        $response->assertOk();
        $response->assertSee('Surat & SK');
        $response->assertSee('001/SK/X/2026');
        $response->assertSee('Surat Keputusan Anggota');
    }

    public function test_admin_bisa_membuat_surat_dengan_nomor_otomatis(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.surat.store'), [
            'jenis' => 'sk',
            'tanggal' => '2026-10-05',
            'perihal' => 'SK Anggota Batalyon',
            'penandatangan' => 'Admin Staf Batalyon',
            'jabatan' => 'Ketua Batalyon',
            'tempat' => 'Pariaman',
        ])->assertRedirect(route('admin.surat.index'));

        $this->assertDatabaseHas('surats', [
            'perihal' => 'SK Anggota Batalyon',
            'nomor' => '001/SK/X/2026',
            'nomor_urut' => 1,
        ]);
    }

    public function test_nomor_urut_bertambah_sesuai_jenis_dan_tahun(): void
    {
        $admin = User::factory()->admin()->create();

        Surat::create([
            'jenis' => 'sk',
            'nomor_urut' => 1,
            'nomor' => '001/SK/X/2026',
            'tanggal' => '2026-10-01',
            'perihal' => 'SK Satu',
            'penandatangan' => 'Admin',
            'jabatan' => 'Ketua',
            'tempat' => 'Pariaman',
        ]);

        $this->actingAs($admin)->post(route('admin.surat.store'), [
            'jenis' => 'sk',
            'tanggal' => '2026-10-05',
            'perihal' => 'SK Dua',
            'penandatangan' => 'Admin',
            'jabatan' => 'Ketua',
            'tempat' => 'Pariaman',
        ])->assertRedirect(route('admin.surat.index'));

        $suratBaru = Surat::where('perihal', 'SK Dua')->firstOrFail();
        $this->assertSame(2, $suratBaru->nomor_urut);
        $this->assertSame('002/SK/X/2026', $suratBaru->nomor);
    }

    public function test_nomor_manual_dipakai_apa_adanya(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.surat.store'), [
            'jenis' => 'tugas',
            'tanggal' => '2026-10-05',
            'perihal' => 'Surat Tugas Piket',
            'nomor' => 'MAN/TUGAS/2026',
            'penandatangan' => 'Admin',
            'jabatan' => 'Sekretaris',
            'tempat' => 'Pariaman',
        ])->assertRedirect(route('admin.surat.index'));

        $this->assertDatabaseHas('surats', [
            'perihal' => 'Surat Tugas Piket',
            'nomor' => 'MAN/TUGAS/2026',
            'nomor_urut' => null,
        ]);
    }

    public function test_nomor_duplikat_ditolak(): void
    {
        $admin = User::factory()->admin()->create();
        Surat::create([
            'jenis' => 'sk',
            'nomor_urut' => 1,
            'nomor' => '001/SK/X/2026',
            'tanggal' => '2026-10-01',
            'perihal' => 'SK Satu',
            'penandatangan' => 'Admin',
            'jabatan' => 'Ketua',
            'tempat' => 'Pariaman',
        ]);

        $this->actingAs($admin)->post(route('admin.surat.store'), [
            'jenis' => 'sk',
            'tanggal' => '2026-10-05',
            'perihal' => 'SK Duplikat',
            'nomor' => '001/SK/X/2026',
            'penandatangan' => 'Admin',
            'jabatan' => 'Ketua',
            'tempat' => 'Pariaman',
        ])->assertSessionHasErrors('nomor');

        $this->assertDatabaseMissing('surats', ['perihal' => 'SK Duplikat']);
    }

    public function test_validasi_surat_menolak_isian_kosong(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.surat.store'), [
            'jenis' => 'sk',
        ])->assertSessionHasErrors(['tanggal', 'perihal', 'penandatangan', 'jabatan', 'tempat']);
    }

    public function test_admin_bisa_mengubah_surat(): void
    {
        $admin = User::factory()->admin()->create();
        $surat = Surat::create([
            'jenis' => 'sk',
            'nomor_urut' => 1,
            'nomor' => '001/SK/X/2026',
            'tanggal' => '2026-10-01',
            'perihal' => 'SK Awal',
            'penandatangan' => 'Admin',
            'jabatan' => 'Ketua',
            'tempat' => 'Pariaman',
        ]);

        $this->actingAs($admin)->patch(route('admin.surat.update', $surat), [
            'jenis' => 'sk',
            'nomor' => '001/SK/X/2026',
            'tanggal' => '2026-10-02',
            'perihal' => 'SK Diperbarui',
            'penandatangan' => 'Admin Baru',
            'jabatan' => 'Wakil Ketua',
            'tempat' => 'Pariaman',
        ])->assertRedirect(route('admin.surat.index'));

        $this->assertSame('SK Diperbarui', $surat->fresh()->perihal);
        $this->assertSame('Admin Baru', $surat->fresh()->penandatangan);
    }

    public function test_admin_bisa_menghapus_surat(): void
    {
        $admin = User::factory()->admin()->create();
        $surat = Surat::create([
            'jenis' => 'undangan',
            'nomor_urut' => 1,
            'nomor' => '001/SU/X/2026',
            'tanggal' => '2026-10-01',
            'perihal' => 'Undangan Rapat',
            'penandatangan' => 'Admin',
            'jabatan' => 'Ketua',
            'tempat' => 'Pariaman',
        ]);

        $this->actingAs($admin)->delete(route('admin.surat.destroy', $surat))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('surats', 0);
    }

    public function test_surat_bisa_ditautkan_ke_periode_dan_event(): void
    {
        $admin = User::factory()->admin()->create();
        $periode = $this->periode();
        $event = Event::create([
            'nama' => 'Latihan Dasar',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-08-01',
            'status' => 'rencana',
        ]);

        $this->actingAs($admin)->post(route('admin.surat.store'), [
            'jenis' => 'tugas',
            'tanggal' => '2026-10-05',
            'perihal' => 'Surat Tugas Latihan Dasar',
            'penandatangan' => 'Admin',
            'jabatan' => 'Ketua',
            'tempat' => 'Pariaman',
            'periode_id' => $periode->id,
            'event_id' => $event->id,
        ])->assertRedirect(route('admin.surat.index'));

        $this->assertDatabaseHas('surats', [
            'perihal' => 'Surat Tugas Latihan Dasar',
            'periode_id' => $periode->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_cetak_surat_mengembalikan_pdf(): void
    {
        $admin = User::factory()->admin()->create();
        $surat = Surat::create([
            'jenis' => 'sk',
            'nomor_urut' => 1,
            'nomor' => '001/SK/X/2026',
            'tanggal' => '2026-10-01',
            'perihal' => 'SK Anggota',
            'penandatangan' => 'Admin',
            'jabatan' => 'Ketua',
            'tempat' => 'Pariaman',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.surat.cetak', $surat));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_staf_tidak_bisa_mengakses_surat(): void
    {
        $staf = User::factory()->create();

        $this->actingAs($staf)->get(route('admin.surat.index'))->assertForbidden();
        $this->actingAs($staf)->get(route('admin.surat.create'))->assertForbidden();
        $this->actingAs($staf)->post(route('admin.surat.store'), [])->assertForbidden();
    }
}
