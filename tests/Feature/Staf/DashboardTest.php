<?php

namespace Tests\Feature\Staf;

use App\Models\Bidang;
use App\Models\Event;
use App\Models\PiketGerbang;
use App\Models\StafProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat profil staf pada satu bidang.
     */
    private function profil(User $staf, ?Bidang $bidang = null, ?string $jabatan = null): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
            'jabatan' => $jabatan,
            'is_inti' => false,
        ]);
    }

    /**
     * Jadwalkan piket gerbang untuk seorang staf.
     */
    private function piket(User $staf, string $tanggal, ?string $catatan = null): PiketGerbang
    {
        return PiketGerbang::create([
            'tanggal' => $tanggal,
            'sesi' => 'harian',
            'user_id' => $staf->id,
            'catatan' => $catatan,
        ]);
    }

    /**
     * Label tanggal seperti yang tampil di halaman, contoh "Selasa, 13 Oktober 2026".
     */
    private function label(string $tanggal): string
    {
        return Carbon::parse($tanggal)->locale('id')->translatedFormat('l, j F Y');
    }

    /**
     * Dashboard hanya menampilkan piket milik staf yang sedang masuk.
     */
    public function test_staf_hanya_melihat_piket_miliknya_sendiri(): void
    {
        $staf = User::factory()->create(['name' => 'Sari Piket']);
        $stafLain = User::factory()->create(['name' => 'Budi Piket']);

        $milikSendiri = now()->toDateString();
        $milikOrangLain = now()->addWeek()->toDateString();

        $this->piket($staf, $milikSendiri, 'Datang lebih awal');
        $this->piket($stafLain, $milikOrangLain);

        $response = $this->actingAs($staf)->get(route('staf.dashboard'));

        $response->assertOk();
        $response->assertSee('Jadwal Piket Saya');
        $response->assertSee('Datang lebih awal');
        $response->assertSee($this->label($milikSendiri));

        $response->assertDontSee('Budi Piket');
        $response->assertDontSee($this->label($milikOrangLain));
    }

    /**
     * Piket hari ini diberi penanda "Hari ini giliran Anda".
     */
    public function test_dashboard_menandai_giliran_hari_ini(): void
    {
        $staf = User::factory()->create();
        $this->piket($staf, now()->toDateString());

        $response = $this->actingAs($staf)->get(route('staf.dashboard'));

        $response->assertOk();
        $response->assertSee('Hari ini giliran Anda');
    }

    /**
     * Piket minggu depan tidak diberi penanda hari ini.
     */
    public function test_piket_minggu_depan_tidak_ditandai_hari_ini(): void
    {
        $staf = User::factory()->create();
        $this->piket($staf, now()->addWeek()->toDateString());

        $response = $this->actingAs($staf)->get(route('staf.dashboard'));

        $response->assertOk();
        $response->assertDontSee('Hari ini giliran Anda');
    }

    /**
     * Jadwal piket mencakup tiga minggu ke depan, tidak lebih.
     */
    public function test_jadwal_mencakup_tiga_minggu_ke_depan(): void
    {
        $staf = User::factory()->create();

        $tigaMinggu = now()->addWeeks(3)->toDateString();
        $limaMinggu = now()->addWeeks(5)->toDateString();

        $this->piket($staf, $tigaMinggu);
        $this->piket($staf, $limaMinggu);

        $response = $this->actingAs($staf)->get(route('staf.dashboard'));

        $response->assertOk();
        $response->assertSee($this->label($tigaMinggu));
        $response->assertDontSee($this->label($limaMinggu));
    }

    /**
     * Kartu kegiatan hanya memuat kegiatan rencana dan berjalan.
     */
    public function test_kegiatan_terdekat_hanya_rencana_dan_berjalan(): void
    {
        $staf = User::factory()->create();

        Event::create([
            'nama' => 'Rapat Rencana',
            'tahun' => 2026,
            'tanggal_mulai' => now()->addWeek()->toDateString(),
            'tanggal_selesai' => now()->addWeek()->toDateString(),
            'status' => 'rencana',
        ]);

        Event::create([
            'nama' => 'Bakti Berjalan',
            'tahun' => 2026,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDay()->toDateString(),
            'status' => 'berjalan',
        ]);

        Event::create([
            'nama' => 'Acara Selesai',
            'tahun' => 2026,
            'tanggal_mulai' => now()->subWeek()->toDateString(),
            'tanggal_selesai' => now()->subWeek()->toDateString(),
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($staf)->get(route('staf.dashboard'));

        $response->assertOk();
        $response->assertSee('Kegiatan Terdekat');
        $response->assertSee('Rapat Rencana');
        $response->assertSee('Rencana');
        $response->assertSee('Bakti Berjalan');
        $response->assertSee('Berjalan');
        $response->assertDontSee('Acara Selesai');
    }

    /**
     * Sapaan memuat nama, bidang, dan jabatan staf.
     */
    public function test_sapaan_menampilkan_bidang_dan_jabatan(): void
    {
        $humas = Bidang::create(['nama' => 'Humas']);
        $staf = User::factory()->create(['name' => 'Rina Sapaan']);
        $this->profil($staf, $humas, 'Koordinator Humas');

        $response = $this->actingAs($staf)->get(route('staf.dashboard'));

        $response->assertOk();
        $response->assertSee('Rina Sapaan');
        $response->assertSee('Koordinator Humas');
        $response->assertSee('Humas');
    }

    /**
     * Sapaan memuat kelas staf kalau sudah diisi.
     */
    public function test_sapaan_menampilkan_kelas(): void
    {
        $staf = User::factory()->create(['name' => 'Rina Sapaan']);
        StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => null,
            'kelas' => 'XI RPL 2',
            'jabatan' => 'Anggota',
            'is_inti' => false,
        ]);

        $response = $this->actingAs($staf)->get(route('staf.dashboard'));

        $response->assertOk();
        $response->assertSee('XI RPL 2');
    }

    /**
     * Dashbord tetap tampil walau staf belum punya profil atau jadwal piket.
     */
    public function test_dashboard_tetap_tampil_tanpa_profil_dan_jadwal(): void
    {
        $staf = User::factory()->create(['name' => 'Staf Baru']);

        $response = $this->actingAs($staf)->get(route('staf.dashboard'));

        $response->assertOk();
        $response->assertSee('Staf Baru');
        $response->assertSee('Belum ada jadwal piket untuk Anda.');
    }

    /**
     * Admin tidak boleh membuka dashboard staf.
     */
    public function test_admin_tidak_bisa_membuka_dashboard_staf(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/staf')->assertForbidden();
        $this->actingAs($admin)->get(route('staf.dashboard'))->assertForbidden();
    }
}
