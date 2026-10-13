<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin bisa membuka dashboard dan melihat ringkasan.
     */
    public function test_admin_can_open_dashboard_with_summary(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(2)->create();
        User::factory()->pending()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Staf Aktif');
        $response->assertSee('Menunggu Persetujuan');
        $response->assertSee('Piket Minggu Ini');

        $this->assertSame(
            User::where('role', 'staf')->where('status', 'approved')->count(),
            $response->viewData('jumlahStaf')
        );
        // Akun admin tidak ikut dihitung di kartu "Staf Aktif".
        $this->assertSame(2, $response->viewData('jumlahStaf'));
        $this->assertSame(1, $response->viewData('jumlahPending'));
        $this->assertSame(0, $response->viewData('jumlahEvent'));
        $this->assertSame(0, $response->viewData('jumlahPiketMingguIni'));
    }

    /**
     * Jumlah event ikut terhitung.
     */
    public function test_dashboard_counts_events(): void
    {
        $admin = User::factory()->admin()->create();

        Event::create([
            'nama' => 'Peringatan HUT Sekolah',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-02',
            'status' => 'rencana',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $this->assertSame(1, $response->viewData('jumlahEvent'));
    }

    /**
     * Tautan di kartu menunggu persetujuan menuju Kelola Akun dengan filter pending.
     */
    public function test_dashboard_links_to_pending_filter(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(route('admin.akun.index', ['status' => 'pending']), false);
    }
}
