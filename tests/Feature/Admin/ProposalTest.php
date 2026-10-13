<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProposalTest extends TestCase
{
    use RefreshDatabase;

    private function buatEvent(string $nama = 'Pentas Seni', int $tahun = 2026, string $status = 'rencana'): Event
    {
        return Event::create([
            'nama' => $nama,
            'tahun' => $tahun,
            'tanggal_mulai' => $tahun.'-05-01',
            'tanggal_selesai' => $tahun.'-05-02',
            'status' => $status,
        ]);
    }

    private function berkasPdf(string $nama = 'proposal.pdf'): UploadedFile
    {
        return UploadedFile::fake()->create($nama, 100, 'application/pdf');
    }

    public function test_admin_can_upload_proposal(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event = $this->buatEvent();

        $response = $this->actingAs($admin)->post(route('admin.proposal.store', $event), [
            'judul' => 'Proposal Pentas Seni',
            'catatan' => 'Draft pertama',
            'file' => $this->berkasPdf(),
        ]);

        $response->assertRedirect(route('admin.event.show', $event));
        $response->assertSessionHas('success');

        $proposal = Proposal::first();
        $this->assertNotNull($proposal);
        $this->assertSame('Proposal Pentas Seni', $proposal->judul);
        $this->assertSame('Draft pertama', $proposal->catatan);
        $this->assertSame($event->id, $proposal->event_id);
        $this->assertNull($proposal->template_dari_id);
        Storage::disk('public')->assertExists($proposal->file_path);
    }

    public function test_proposal_file_type_outside_pdf_doc_docx_is_rejected(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event = $this->buatEvent();

        $response = $this->actingAs($admin)->post(route('admin.proposal.store', $event), [
            'judul' => 'Proposal Salah Tipe',
            'file' => UploadedFile::fake()->create('gambar.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('proposals', 0);
        $this->assertCount(0, Storage::disk('public')->allFiles());
    }

    public function test_proposal_file_larger_than_ten_megabytes_is_rejected(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event = $this->buatEvent();

        $response = $this->actingAs($admin)->post(route('admin.proposal.store', $event), [
            'judul' => 'Proposal Terlalu Besar',
            'file' => UploadedFile::fake()->create('besar.pdf', 11000, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('proposals', 0);
    }

    public function test_admin_can_create_new_proposal_based_on_old_proposal(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $asalEvent = $this->buatEvent('Pentas Seni', 2025, 'selesai');
        $tujuanEvent = $this->buatEvent('Pentas Seni', 2026, 'rencana');

        $pathAsal = $this->berkasPdf('proposal-lama.pdf')->store('proposals', 'public');

        $asal = Proposal::create([
            'event_id' => $asalEvent->id,
            'judul' => 'Proposal Pentas Seni 2025',
            'file_path' => $pathAsal,
            'catatan' => 'Proposal lama',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.proposal.dasar.store', $asal), [
            'event_id' => $tujuanEvent->id,
            'judul' => 'Proposal Pentas Seni 2026',
            'catatan' => 'Menyalin dari proposal 2025',
        ]);

        $response->assertRedirect(route('admin.event.show', $tujuanEvent));
        $response->assertSessionHas('success');

        $baru = Proposal::where('event_id', $tujuanEvent->id)->first();
        $this->assertNotNull($baru);
        $this->assertSame($asal->id, $baru->template_dari_id);
        $this->assertSame('Proposal Pentas Seni 2026', $baru->judul);

        // Berkas disalin, bukan dipakai bersama.
        $this->assertNotSame($asal->file_path, $baru->file_path);
        Storage::disk('public')->assertExists($baru->file_path);
        Storage::disk('public')->assertExists($pathAsal);
    }

    public function test_event_detail_shows_based_on_label_for_derived_proposal(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $asalEvent = $this->buatEvent('Pentas Seni', 2025, 'selesai');
        $tujuanEvent = $this->buatEvent('Pentas Seni', 2026, 'rencana');

        $asal = Proposal::create([
            'event_id' => $asalEvent->id,
            'judul' => 'Proposal Dasar 2025',
            'file_path' => 'proposals/dasar.pdf',
        ]);

        $this->actingAs($admin)->post(route('admin.proposal.dasar.store', $asal), [
            'event_id' => $tujuanEvent->id,
            'judul' => 'Proposal Turunan 2026',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.event.show', $tujuanEvent));

        $response->assertOk();
        $response->assertSee('Berdasarkan:');
        $response->assertSee('Proposal Dasar 2025');
    }

    public function test_deleting_proposal_deletes_its_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event = $this->buatEvent();

        $path = $this->berkasPdf()->store('proposals', 'public');

        $proposal = Proposal::create([
            'event_id' => $event->id,
            'judul' => 'Proposal Akan Dihapus',
            'file_path' => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($admin)
            ->from(route('admin.event.show', $event))
            ->delete(route('admin.proposal.destroy', $proposal));

        $response->assertRedirect(route('admin.event.show', $event));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('proposals', ['id' => $proposal->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_deleting_parent_proposal_empties_the_child_reference(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event = $this->buatEvent();
        $eventLain = $this->buatEvent('Kegiatan Lain', 2026, 'rencana');

        $asal = Proposal::create([
            'event_id' => $event->id,
            'judul' => 'Proposal Induk',
            'file_path' => 'proposals/induk.pdf',
        ]);

        $turunan = Proposal::create([
            'event_id' => $eventLain->id,
            'judul' => 'Proposal Turunan',
            'file_path' => 'proposals/turunan.pdf',
            'template_dari_id' => $asal->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.proposal.destroy', $asal));

        $response->assertRedirect(route('admin.event.show', $event));

        $this->assertDatabaseMissing('proposals', ['id' => $asal->id]);
        $this->assertDatabaseHas('proposals', ['id' => $turunan->id]);
        $this->assertNull($turunan->fresh()->template_dari_id);
    }

    public function test_arsip_can_be_searched_and_filtered_by_year(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event2025 = $this->buatEvent('Pentas Seni', 2025, 'selesai');
        $event2026 = $this->buatEvent('Lomba Kebersihan', 2026, 'rencana');

        Proposal::create([
            'event_id' => $event2025->id,
            'judul' => 'Proposal Pentas Seni 2025',
            'file_path' => 'proposals/a.pdf',
        ]);

        Proposal::create([
            'event_id' => $event2026->id,
            'judul' => 'Proposal Lomba Kebersihan 2026',
            'file_path' => 'proposals/b.pdf',
        ]);

        $this->actingAs($admin)->get(route('admin.arsip.index'))
            ->assertOk()
            ->assertSee('Proposal Pentas Seni 2025')
            ->assertSee('Proposal Lomba Kebersihan 2026');

        $this->actingAs($admin)->get(route('admin.arsip.index', ['tahun' => 2025]))
            ->assertOk()
            ->assertSee('Proposal Pentas Seni 2025')
            ->assertDontSee('Proposal Lomba Kebersihan 2026');

        $this->actingAs($admin)->get(route('admin.arsip.index', ['q' => 'Kebersihan']))
            ->assertOk()
            ->assertSee('Proposal Lomba Kebersihan 2026')
            ->assertDontSee('Proposal Pentas Seni 2025');
    }

    public function test_admin_can_download_proposal_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event = $this->buatEvent();

        $path = $this->berkasPdf()->store('proposals', 'public');

        $proposal = Proposal::create([
            'event_id' => $event->id,
            'judul' => 'Proposal Unduhan',
            'file_path' => $path,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.arsip.unduh', $proposal));

        $response->assertOk();
        $response->assertDownload('Proposal Unduhan.pdf');
    }

    public function test_download_shows_error_when_file_is_missing(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event = $this->buatEvent();

        $proposal = Proposal::create([
            'event_id' => $event->id,
            'judul' => 'Proposal Hilang',
            'file_path' => 'proposals/tidak-ada.pdf',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.arsip.unduh', $proposal));

        $response->assertRedirect(route('admin.arsip.index'));
        $response->assertSessionHas('error');
    }
}
