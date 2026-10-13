<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
use App\Models\Event;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Staf biasa tidak boleh membuka halaman admin mana pun.
     */
    public function test_staf_cannot_open_any_admin_page(): void
    {
        $staf = User::factory()->create();

        $halaman = [
            '/admin',
            '/admin/dashboard',
            '/admin/akun',
            '/admin/staf',
            '/admin/staf/tambah',
            '/admin/bidang',
            '/admin/bidang/tambah',
            '/admin/event',
            '/admin/event/tambah',
            '/admin/arsip',
        ];

        foreach ($halaman as $url) {
            $this->actingAs($staf)->get($url)->assertForbidden();
        }
    }

    /**
     * Staf biasa juga tidak boleh menjalankan aksi admin.
     */
    public function test_staf_cannot_run_admin_actions(): void
    {
        $staf = User::factory()->create();
        $target = User::factory()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);

        $this->actingAs($staf)
            ->from('/staf/dashboard')
            ->post(route('admin.akun.reset-password', $target))
            ->assertForbidden();

        $this->actingAs($staf)
            ->from('/staf/dashboard')
            ->patch(route('admin.staf.update', $target), ['bidang_id' => $bidang->id])
            ->assertForbidden();

        $this->actingAs($staf)
            ->from('/staf/dashboard')
            ->delete(route('admin.bidang.destroy', $bidang))
            ->assertForbidden();

        $this->assertSame('approved', $target->fresh()->status);
        $this->assertDatabaseHas('bidangs', ['id' => $bidang->id]);
    }

    /**
     * Staf biasa tidak boleh mengelola kegiatan dan proposal.
     */
    public function test_staf_cannot_manage_events_and_proposals(): void
    {
        $staf = User::factory()->create();

        $event = Event::create([
            'nama' => 'Kegiatan Lama',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-03-01',
            'status' => 'rencana',
        ]);

        $proposal = Proposal::create([
            'event_id' => $event->id,
            'judul' => 'Proposal Lama',
            'file_path' => 'proposals/contoh.pdf',
        ]);

        $this->actingAs($staf)
            ->post(route('admin.event.store'), [
                'nama' => 'Kegiatan Curang',
                'tahun' => 2026,
                'tanggal_mulai' => '2026-04-01',
                'status' => 'rencana',
            ])
            ->assertForbidden();

        $this->actingAs($staf)
            ->delete(route('admin.proposal.destroy', $proposal))
            ->assertForbidden();

        $this->actingAs($staf)
            ->get(route('admin.arsip.unduh', $proposal))
            ->assertForbidden();

        $this->assertDatabaseMissing('events', ['nama' => 'Kegiatan Curang']);
        $this->assertDatabaseHas('proposals', ['id' => $proposal->id]);
    }

    /**
     * Guest diarahkan ke halaman masuk, bukan halaman admin.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }
}
