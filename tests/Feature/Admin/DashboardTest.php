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
     * Sebaran kelas hanya menghitung staf aktif dan mengabaikan yang kosong.
     */
    public function test_dashboard_shows_sebaran_kelas(): void
    {
        $admin = User::factory()->admin()->create();

        foreach (['Siti Aminah', 'Budi Santoso'] as $nama) {
            $staf = User::factory()->create(['name' => $nama]);
            $staf->stafProfile()->create([
                'bidang_id' => null,
                'kelas' => 'XI RPL 1',
                'is_inti' => false,
            ]);
        }

        $stafLain = User::factory()->create(['name' => 'Andi Wijaya']);
        $stafLain->stafProfile()->create([
            'bidang_id' => null,
            'kelas' => 'X TKJ 2',
            'is_inti' => false,
        ]);

        // Staf pending tidak ikut dihitung walau kelasnya terisi.
        $pending = User::factory()->pending()->create(['name' => 'Pendaftar Baru']);
        $pending->stafProfile()->create([
            'bidang_id' => null,
            'kelas' => 'X TKJ 2',
            'is_inti' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Sebaran Kelas');
        $response->assertSee('XI RPL 1');
        // Tiap kelas bisa diklik menuju Data Staf yang tersaring.
        $response->assertSee(route('admin.staf.index', ['kelas' => 'XI RPL 1']), false);

        $this->assertSame(
            ['XI RPL 1' => 2, 'X TKJ 2' => 1],
            $response->viewData('sebaranKelas')->all()
        );
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
