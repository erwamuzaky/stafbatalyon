<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    private function dataEvent(array $ganti = []): array
    {
        return array_merge([
            'nama' => 'Pentas Seni',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-05-01',
            'tanggal_selesai' => '2026-05-02',
            'status' => 'rencana',
        ], $ganti);
    }

    public function test_admin_can_create_event(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.event.store'), $this->dataEvent());

        $response->assertRedirect(route('admin.event.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('events', ['nama' => 'Pentas Seni', 'status' => 'rencana']);
    }

    public function test_tanggal_selesai_is_optional(): void
    {
        $admin = User::factory()->admin()->create();

        // Tanggal selesai boleh kosong.
        $response = $this->actingAs($admin)->post(route('admin.event.store'), $this->dataEvent([
            'tanggal_selesai' => null,
        ]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('events', ['nama' => 'Pentas Seni', 'tanggal_selesai' => null]);
    }

    public function test_event_requires_nama_tahun_tanggal_and_status(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.event.store'), [
            'nama' => '',
            'tahun' => '',
            'tanggal_mulai' => '',
            'status' => 'entah',
        ]);

        $response->assertSessionHasErrors(['nama', 'tahun', 'tanggal_mulai', 'status']);
        $this->assertDatabaseCount('events', 0);
    }

    public function test_tanggal_selesai_cannot_be_before_tanggal_mulai(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.event.store'), $this->dataEvent([
            'tanggal_mulai' => '2026-05-10',
            'tanggal_selesai' => '2026-05-01',
        ]));

        $response->assertSessionHasErrors('tanggal_selesai');
        $this->assertDatabaseCount('events', 0);
    }

    public function test_event_index_can_be_searched_and_filtered(): void
    {
        $admin = User::factory()->admin()->create();

        Event::create($this->dataEvent(['nama' => 'Pentas Seni', 'tahun' => 2026, 'status' => 'rencana']));
        Event::create($this->dataEvent([
            'nama' => 'Lomba Kebersihan',
            'tahun' => 2025,
            'tanggal_mulai' => '2025-08-01',
            'tanggal_selesai' => null,
            'status' => 'selesai',
        ]));

        // Tanpa filter: dua-duanya tampil.
        $this->actingAs($admin)->get(route('admin.event.index'))
            ->assertOk()
            ->assertSee('Pentas Seni')
            ->assertSee('Lomba Kebersihan');

        // Filter tahun 2025.
        $this->actingAs($admin)->get(route('admin.event.index', ['tahun' => 2025]))
            ->assertOk()
            ->assertSee('Lomba Kebersihan')
            ->assertDontSee('Pentas Seni');

        // Filter status selesai.
        $this->actingAs($admin)->get(route('admin.event.index', ['status' => 'selesai']))
            ->assertOk()
            ->assertSee('Lomba Kebersihan')
            ->assertDontSee('Pentas Seni');

        // Pencarian nama.
        $this->actingAs($admin)->get(route('admin.event.index', ['q' => 'Pentas']))
            ->assertOk()
            ->assertSee('Pentas Seni')
            ->assertDontSee('Lomba Kebersihan');
    }

    public function test_admin_can_update_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::create($this->dataEvent());

        $response = $this->actingAs($admin)->patch(route('admin.event.update', $event), $this->dataEvent([
            'nama' => 'Pentas Seni Akhir Tahun',
            'status' => 'berjalan',
        ]));

        $response->assertRedirect(route('admin.event.show', $event));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'nama' => 'Pentas Seni Akhir Tahun',
            'status' => 'berjalan',
        ]);
    }

    public function test_event_detail_shows_proposals(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::create($this->dataEvent());

        Proposal::create([
            'event_id' => $event->id,
            'judul' => 'Proposal Pentas Seni',
            'file_path' => 'proposals/contoh.pdf',
            'catatan' => 'Draft pertama',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.event.show', $event));

        $response->assertOk();
        $response->assertSee('Pentas Seni');
        $response->assertSee('Proposal Pentas Seni');
        $response->assertSee('Draft pertama');
    }

    public function test_deleting_event_deletes_its_proposals_and_files(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $event = Event::create($this->dataEvent());

        $path = UploadedFile::fake()
            ->create('proposal.pdf', 100, 'application/pdf')
            ->store('proposals', 'public');

        $proposal = Proposal::create([
            'event_id' => $event->id,
            'judul' => 'Proposal Pentas Seni',
            'file_path' => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($admin)->delete(route('admin.event.destroy', $event));

        $response->assertRedirect(route('admin.event.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
        $this->assertDatabaseMissing('proposals', ['id' => $proposal->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
