<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
use App\Models\PiketGerbang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PiketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat entri piket langsung lewat model.
     */
    private function entri(User $staf, string $tanggal, string $sesi = 'harian', ?string $catatan = null): PiketGerbang
    {
        return PiketGerbang::create([
            'tanggal' => $tanggal,
            'sesi' => $sesi,
            'user_id' => $staf->id,
            'catatan' => $catatan,
        ]);
    }

    /**
     * Admin bisa menambah piket untuk beberapa staf sekaligus.
     */
    public function test_admin_can_add_piket_for_several_staf_at_once(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->count(3)->create();

        $response = $this->actingAs($admin)->post(route('admin.piket.store'), [
            'tanggal' => '2026-10-13',
            'sesi' => 'harian',
            'user_ids' => $staf->pluck('id')->all(),
            'catatan' => 'Bawa buku piket',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.piket.index', ['minggu' => '2026-10-13']));

        $this->assertDatabaseCount('piket_gerbang', 3);
        $this->assertDatabaseHas('piket_gerbang', [
            'sesi' => 'harian',
            'user_id' => $staf->first()->id,
            'catatan' => 'Bawa buku piket',
        ]);
    }

    /**
     * Staf yang sama tidak boleh dua kali pada tanggal yang sama.
     */
    public function test_duplicate_piket_on_the_same_slot_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create();

        $this->entri($staf, '2026-10-13', 'harian');

        $response = $this->actingAs($admin)->post(route('admin.piket.store'), [
            'tanggal' => '2026-10-13',
            'sesi' => 'harian',
            'user_ids' => [$staf->id],
        ]);

        $response->assertSessionHasErrors('user_ids');
        $this->assertDatabaseCount('piket_gerbang', 1);
    }

    /**
     * Hanya akun role staf yang sudah disetujui yang bisa dijadwalkan.
     */
    public function test_only_approved_staf_can_be_assigned(): void
    {
        $admin = User::factory()->admin()->create();
        $pending = User::factory()->pending()->create();

        // Staf yang masih menunggu persetujuan ditolak.
        $this->actingAs($admin)->post(route('admin.piket.store'), [
            'tanggal' => '2026-10-13',
            'sesi' => 'harian',
            'user_ids' => [$pending->id],
        ])->assertSessionHasErrors();

        // Akun admin juga bukan pilihan yang sah.
        $this->actingAs($admin)->post(route('admin.piket.store'), [
            'tanggal' => '2026-10-13',
            'sesi' => 'harian',
            'user_ids' => [$admin->id],
        ])->assertSessionHasErrors();

        $this->assertDatabaseCount('piket_gerbang', 0);
    }

    /**
     * Salin jadwal satu minggu ke minggu berikutnya dan lewati yang sudah ada.
     */
    public function test_copy_week_creates_schedule_for_next_week(): void
    {
        $admin = User::factory()->admin()->create();
        $a = User::factory()->create();
        $b = User::factory()->create();

        $this->entri($a, '2026-10-12', 'harian', 'Kunci gerbang');
        $this->entri($b, '2026-10-14', 'harian');

        $response = $this->actingAs($admin)->post(route('admin.piket.salin'), [
            'minggu' => '2026-10-12',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.piket.index', ['minggu' => '2026-10-19']));

        $this->assertDatabaseCount('piket_gerbang', 4);

        $this->assertTrue(PiketGerbang::whereDate('tanggal', '2026-10-19')
            ->where('sesi', 'harian')
            ->where('user_id', $a->id)
            ->exists());

        $this->assertTrue(PiketGerbang::whereDate('tanggal', '2026-10-21')
            ->where('sesi', 'harian')
            ->where('user_id', $b->id)
            ->exists());

        // Menyalin ulang minggu yang sama tidak menambah baris baru.
        $this->actingAs($admin)->post(route('admin.piket.salin'), [
            'minggu' => '2026-10-12',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('piket_gerbang', 4);
    }

    /**
     * Staf biasa tidak boleh membuka halaman piket gerbang.
     */
    public function test_staf_cannot_open_piket_pages(): void
    {
        $staf = User::factory()->create();
        $lain = User::factory()->create();
        $entri = $this->entri($lain, '2026-10-13');

        $this->actingAs($staf)->get('/admin/piket')->assertForbidden();
        $this->actingAs($staf)->get('/admin/piket/tambah')->assertForbidden();
        $this->actingAs($staf)->get(route('admin.piket.edit', $entri))->assertForbidden();

        $this->actingAs($staf)->post(route('admin.piket.store'), [
            'tanggal' => '2026-10-13',
            'sesi' => 'harian',
            'user_ids' => [$staf->id],
        ])->assertForbidden();

        $this->actingAs($staf)->post(route('admin.piket.salin'), [
            'minggu' => '2026-10-12',
        ])->assertForbidden();

        $this->actingAs($staf)->delete(route('admin.piket.destroy', $entri))->assertForbidden();

        $this->assertDatabaseCount('piket_gerbang', 1);
    }

    /**
     * Tampilan mingguan memuat nama staf dan tombol navigasi minggu.
     */
    public function test_weekly_view_shows_schedule_and_navigation(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create(['name' => 'Rina Piket']);

        $this->entri($staf, '2026-10-13', 'harian', 'Datang lebih awal');

        $response = $this->actingAs($admin)->get(route('admin.piket.index', ['minggu' => '2026-10-12']));

        $response->assertOk();
        $response->assertSee('Jadwal Piket Gerbang');
        $response->assertSee('Rina Piket');
        $response->assertSee('Datang lebih awal');
        $response->assertSee('Senin');
        $response->assertSee('Sabtu');
        $response->assertSee('Staf Piket');
        $response->assertDontSee('Siang');
        $response->assertSee('Minggu Sebelumnya');
        $response->assertSee('Minggu Ini');
        $response->assertSee('Minggu Berikutnya');
        $response->assertSee(route('admin.piket.index', ['minggu' => '2026-10-05']), false);
        $response->assertSee(route('admin.piket.index', ['minggu' => '2026-10-19']), false);
    }

    /**
     * Form tambah hanya menampilkan staf yang sudah disetujui.
     */
    public function test_create_form_only_lists_approved_staf(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['name' => 'Staf Disetujui']);
        User::factory()->pending()->create(['name' => 'Staf Menunggu']);

        $response = $this->actingAs($admin)->get(route('admin.piket.create'));

        $response->assertOk();
        $response->assertSee('Staf Disetujui');
        $response->assertDontSee('Staf Menunggu');
    }

    /**
     * Staf yang ditandai tidak ikut piket tidak muncul di pilihan piket.
     */
    public function test_staf_not_joining_piket_is_excluded_from_piket_choices(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Sekretaris']);

        $ikut = User::factory()->create(['name' => 'Staf Ikut Piket']);
        $ikut->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'kelas' => 'X RPL 1',
            'ikut_piket' => true,
        ]);

        $tidakIkut = User::factory()->create(['name' => 'Staf Tanpa Piket']);
        $tidakIkut->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'kelas' => 'X RPL 2',
            'ikut_piket' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.piket.create'));

        $response->assertOk();
        $response->assertSee('Staf Ikut Piket');
        $response->assertDontSee('Staf Tanpa Piket');
    }

    /**
     * Staf yang ditandai tidak ikut piket juga ditolak saat dijadwalkan manual.
     */
    public function test_staf_not_joining_piket_cannot_be_scheduled(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create();
        $staf->stafProfile()->create([
            'bidang_id' => Bidang::create(['nama' => 'Sekretaris'])->id,
            'kelas' => 'X RPL 1',
            'ikut_piket' => false,
        ]);

        $this->actingAs($admin)->post(route('admin.piket.store'), [
            'tanggal' => '2026-10-13',
            'sesi' => 'harian',
            'user_ids' => [$staf->id],
        ])->assertSessionHasErrors();

        $this->assertDatabaseCount('piket_gerbang', 0);
    }

    /**
     * Admin bisa mengubah satu entri piket.
     */
    public function test_admin_can_update_piket_entry(): void
    {
        $admin = User::factory()->admin()->create();
        $a = User::factory()->create();
        $b = User::factory()->create();

        $entri = $this->entri($a, '2026-10-13', 'harian');

        $this->actingAs($admin)->get(route('admin.piket.edit', $entri))
            ->assertOk()
            ->assertSee('Ubah Piket Gerbang');

        $response = $this->actingAs($admin)->patch(route('admin.piket.update', $entri), [
            'tanggal' => '2026-10-14',
            'sesi' => 'harian',
            'user_id' => $b->id,
            'catatan' => 'Ganti jaga',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.piket.index', ['minggu' => '2026-10-14']));

        $entri->refresh();

        $this->assertSame('2026-10-14', $entri->tanggal->toDateString());
        $this->assertSame('harian', $entri->sesi);
        $this->assertSame($b->id, $entri->user_id);
        $this->assertSame('Ganti jaga', $entri->catatan);
    }

    /**
     * Admin bisa menghapus satu entri piket.
     */
    public function test_admin_can_delete_piket_entry(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create();

        $entri = $this->entri($staf, '2026-10-13', 'harian');

        $response = $this->actingAs($admin)->delete(route('admin.piket.destroy', $entri));

        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.piket.index', ['minggu' => '2026-10-13']));

        $this->assertDatabaseMissing('piket_gerbang', ['id' => $entri->id]);
    }
}
