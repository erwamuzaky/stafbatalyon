<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
use App\Models\PiketGerbang;
use App\Models\StafProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PiketRekapTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat entri piket langsung lewat model.
     */
    private function entri(User $staf, string $tanggal, string $sesi = 'harian'): PiketGerbang
    {
        return PiketGerbang::create([
            'tanggal' => $tanggal,
            'sesi' => $sesi,
            'user_id' => $staf->id,
        ]);
    }

    /**
     * Buat profil staf dengan bidang tertentu.
     */
    private function profil(User $staf, ?Bidang $bidang = null): StafProfile
    {
        return StafProfile::create([
            'user_id' => $staf->id,
            'bidang_id' => $bidang?->id,
            'jabatan' => 'Anggota',
            'is_inti' => false,
        ]);
    }

    /**
     * Total piket per staf dihitung benar dan diurut dari terbanyak.
     */
    public function test_total_piket_per_staf_dihitung_dan_diurut(): void
    {
        $admin = User::factory()->admin()->create();
        $rajin = User::factory()->create(['name' => 'Ayu Rajin']);
        $sedikit = User::factory()->create(['name' => 'Budi Sedikit']);

        $this->entri($rajin, '2026-10-05');
        $this->entri($rajin, '2026-10-06');
        $this->entri($rajin, '2026-10-07');
        $this->entri($sedikit, '2026-10-08');

        $response = $this->actingAs($admin)->get(route('admin.piket.rekap', [
            'dari' => '2026-10-01',
            'sampai' => '2026-10-31',
        ]));

        $response->assertOk();
        $response->assertViewHas('tabel', function (array $tabel): bool {
            return count($tabel) === 2
                && $tabel[0]['user']->name === 'Ayu Rajin'
                && $tabel[0]['total'] === 3
                && $tabel[0]['perSesi']['harian'] === 3
                && $tabel[0]['terbanyak'] === true
                && $tabel[1]['user']->name === 'Budi Sedikit'
                && $tabel[1]['total'] === 1
                && $tabel[1]['tersedikit'] === true;
        });

        $response->assertViewHas('ringkasan', function (array $ringkasan): bool {
            return $ringkasan['total'] === 4
                && $ringkasan['jumlahStaf'] === 2
                && $ringkasan['terbanyak'] === 3
                && $ringkasan['tersedikit'] === 1;
        });
    }

    /**
     * Data di luar rentang tanggal tidak ikut dihitung.
     */
    public function test_filter_rentang_menyaring_data_di_luar_rentang(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create(['name' => 'Ayu Rentang']);

        $this->entri($staf, '2026-09-30'); // luar rentang
        $this->entri($staf, '2026-10-05'); // dalam rentang

        $this->actingAs($admin)->get(route('admin.piket.rekap', [
            'dari' => '2026-10-01',
            'sampai' => '2026-10-31',
        ]))->assertViewHas('tabel', function (array $tabel): bool {
            return count($tabel) === 1 && $tabel[0]['total'] === 1;
        });
    }

    /**
     * Penyaring bidang hanya menghitung staf pada bidang itu.
     */
    public function test_filter_bidang_menyaring_staf(): void
    {
        $admin = User::factory()->admin()->create();
        $sekretaris = Bidang::create(['nama' => 'Sekretaris']);
        $humas = Bidang::create(['nama' => 'Humas']);

        $stafSekretaris = User::factory()->create(['name' => 'Sari Sekretaris']);
        $stafHumas = User::factory()->create(['name' => 'Hendra Humas']);
        $this->profil($stafSekretaris, $sekretaris);
        $this->profil($stafHumas, $humas);

        $this->entri($stafSekretaris, '2026-10-05');
        $this->entri($stafHumas, '2026-10-06');

        $response = $this->actingAs($admin)->get(route('admin.piket.rekap', [
            'dari' => '2026-10-01',
            'sampai' => '2026-10-31',
            'bidang_id' => $sekretaris->id,
        ]));

        $response->assertViewHas('tabel', function (array $tabel): bool {
            return count($tabel) === 1 && $tabel[0]['user']->name === 'Sari Sekretaris';
        });

        // Bidang lain tidak boleh muncul pada bagian "belum dapat piket".
        $response->assertViewHas('belum', function (array $belum): bool {
            return collect($belum)->pluck('name')->all() === [];
        });
    }

    /**
     * Deteksi piket pada hari berurutan benar.
     */
    public function test_deteksi_piket_hari_berurutan(): void
    {
        $admin = User::factory()->admin()->create();
        $berurutan = User::factory()->create(['name' => 'Ayu Berurutan']);
        $terpisah = User::factory()->create(['name' => 'Budi Terpisah']);

        $this->entri($berurutan, '2026-10-12');
        $this->entri($berurutan, '2026-10-13');
        $this->entri($berurutan, '2026-10-14');

        // Selisih dua hari, bukan berurutan.
        $this->entri($terpisah, '2026-10-12');
        $this->entri($terpisah, '2026-10-15');

        $response = $this->actingAs($admin)->get(route('admin.piket.rekap', [
            'dari' => '2026-10-01',
            'sampai' => '2026-10-31',
        ]));

        $response->assertViewHas('berurutan', function (array $berurutanData): bool {
            if (count($berurutanData) !== 1) {
                return false;
            }

            $item = $berurutanData[0];

            return $item['user']->name === 'Ayu Berurutan'
                && count($item['rentang']) === 1
                && $item['rentang'][0]['jumlah'] === 3
                && $item['rentang'][0]['dari']->toDateString() === '2026-10-12'
                && $item['rentang'][0]['sampai']->toDateString() === '2026-10-14';
        });
    }

    /**
     * Staf disetujui tanpa piket muncul di bagian belum dapat piket,
     * sedangkan akun pending/rejected tidak ikut.
     */
    public function test_staf_approved_tanpa_piket_muncul_belum_dapat_piket(): void
    {
        $admin = User::factory()->admin()->create();
        $dapat = User::factory()->create(['name' => 'Ayu Dapat']);
        $belumDapat = User::factory()->create(['name' => 'Zaki Kosong']);
        User::factory()->pending()->create(['name' => 'Pandu Menunggu']);
        User::factory()->rejected()->create(['name' => 'Rudi Ditolak']);

        $this->entri($dapat, '2026-10-05');

        $this->actingAs($admin)->get(route('admin.piket.rekap', [
            'dari' => '2026-10-01',
            'sampai' => '2026-10-31',
        ]))->assertViewHas('belum', function (array $belum): bool {
            return collect($belum)->pluck('name')->all() === ['Zaki Kosong'];
        });
    }

    /**
     * Staf biasa tidak boleh membuka halaman rekap maupun cetak PDF-nya.
     */
    public function test_staf_tidak_bisa_membuka_halaman_rekap(): void
    {
        $staf = User::factory()->create();

        $this->actingAs($staf)->get(route('admin.piket.rekap'))->assertForbidden();
        $this->actingAs($staf)->get(route('admin.piket.rekap.cetak'))->assertForbidden();
    }

    /**
     * Halaman rekap memuat ringkasan, tabel, dan tautan cetak PDF.
     */
    public function test_halaman_rekap_memuat_ringkasan_dan_tabel(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create(['name' => 'Rina Rekap']);
        $this->entri($staf, '2026-10-05');

        $response = $this->actingAs($admin)->get(route('admin.piket.rekap', [
            'dari' => '2026-10-01',
            'sampai' => '2026-10-31',
        ]));

        $response->assertOk();
        $response->assertSee('Rekap & Pemerataan Piket Gerbang');
        $response->assertSee('Rina Rekap');
        $response->assertSee('Cetak PDF');
        $response->assertSee('Total Slot Piket');
        $response->assertSee('Staf Dapat Piket');
        $response->assertSee('Belum Dapat Piket');
        $response->assertSee('Piket Hari Berurutan');
    }

    /**
     * Tanpa penyaring, rentang bawaan tetap bisa dibuka
     * (awal bulan berjalan sampai hari ini).
     */
    public function test_halaman_rekap_tanpa_penyaring_memakai_rentang_bawaan(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create(['name' => 'Rina Bawaan']);
        $this->entri($staf, now()->toDateString());

        $response = $this->actingAs($admin)->get(route('admin.piket.rekap'));

        $response->assertOk();
        $response->assertViewHas('dari', fn (Carbon $dari): bool => $dari->isSameDay(now()->startOfMonth()));
        $response->assertViewHas('sampai', fn (Carbon $sampai): bool => $sampai->isSameDay(now()));
        $response->assertViewHas('tabel', function (array $tabel): bool {
            return count($tabel) === 1 && $tabel[0]['user']->name === 'Rina Bawaan';
        });
    }

    /**
     * Rentang tanggal terbalik ditolak dengan pesan kesalahan.
     */
    public function test_rentang_terbalik_ditolak(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.piket.rekap', [
            'dari' => '2026-10-31',
            'sampai' => '2026-10-01',
        ]))->assertSessionHasErrors('sampai');
    }

    /**
     * Endpoint cetak mengembalikan berkas PDF.
     */
    public function test_pdf_rekap_bisa_dirender(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create(['name' => 'Rina Rekap']);
        $this->entri($staf, '2026-10-05');

        $response = $this->actingAs($admin)->get(route('admin.piket.rekap.cetak', [
            'dari' => '2026-10-01',
            'sampai' => '2026-10-31',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    /**
     * Template PDF memuat kop, nama staf, rentang tanggal, dan tanda tangan.
     */
    public function test_template_pdf_memuat_nama_dan_rentang(): void
    {
        $staf = User::factory()->create(['name' => 'Rina Rajin'])->load('stafProfile.bidang');

        $html = view('pdf.rekap-piket', [
            'dari' => Carbon::parse('2026-10-01')->locale('id'),
            'sampai' => Carbon::parse('2026-10-31')->locale('id'),
            'bidangId' => null,
            'namaBidang' => null,
            'bidangPilihan' => collect(),
            'sesi' => PiketGerbang::SESI,
            'tabel' => [[
                'user' => $staf,
                'total' => 2,
                'perSesi' => ['harian' => 2],
                'tanggal' => [Carbon::parse('2026-10-05'), Carbon::parse('2026-10-06')],
                'terbanyak' => false,
                'tersedikit' => false,
            ]],
            'ringkasan' => [
                'total' => 2,
                'jumlahStaf' => 1,
                'rataRata' => 2.0,
                'terbanyak' => 2,
                'tersedikit' => 2,
                'namaTerbanyak' => ['Rina Rajin'],
                'namaTersedikit' => ['Rina Rajin'],
            ],
            'berurutan' => [[
                'user' => $staf,
                'rentang' => [[
                    'dari' => Carbon::parse('2026-10-05'),
                    'sampai' => Carbon::parse('2026-10-06'),
                    'jumlah' => 2,
                ]],
            ]],
            'belum' => [],
            'tempat' => 'Pariaman',
            'jabatan' => 'Sekretaris Batalyon',
            'batasTanggal' => 6,
        ])->render();

        $this->assertStringContainsString('SMKN 3 Pariaman', $html);
        $this->assertStringContainsString('REKAP PIKET GERBANG', $html);
        $this->assertStringContainsString('1 Oktober 2026', $html);
        $this->assertStringContainsString('31 Oktober 2026', $html);
        $this->assertStringContainsString('Rina Rajin', $html);
        $this->assertStringContainsString('Sekretaris Batalyon', $html);
        $this->assertStringContainsString('Pariaman, 31 Oktober 2026', $html);
    }
}
