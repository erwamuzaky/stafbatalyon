<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BidangTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_bidang(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.bidang.store'), [
            'nama' => 'Humas',
        ]);

        $response->assertRedirect(route('admin.bidang.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bidangs', ['nama' => 'Humas']);
    }

    public function test_bidang_nama_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Bidang::create(['nama' => 'Humas']);

        $response = $this->actingAs($admin)->post(route('admin.bidang.store'), [
            'nama' => 'Humas',
        ]);

        $response->assertSessionHasErrors('nama');
    }

    public function test_admin_can_update_bidang(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);

        $response = $this->actingAs($admin)->patch(route('admin.bidang.update', $bidang), [
            'nama' => 'Hubungan Masyarakat',
        ]);

        $response->assertRedirect(route('admin.bidang.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bidangs', ['id' => $bidang->id, 'nama' => 'Hubungan Masyarakat']);
    }

    public function test_admin_can_delete_unused_bidang(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);

        $response = $this->actingAs($admin)
            ->from(route('admin.bidang.index'))
            ->delete(route('admin.bidang.destroy', $bidang));

        $response->assertRedirect(route('admin.bidang.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('bidangs', ['id' => $bidang->id]);
    }

    public function test_bidang_that_is_still_used_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);

        $staf = User::factory()->create();
        $staf->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'jabatan' => 'Ketua',
            'is_inti' => false,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.bidang.index'))
            ->delete(route('admin.bidang.destroy', $bidang));

        $response->assertRedirect(route('admin.bidang.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('bidangs', ['id' => $bidang->id]);
    }

    public function test_bidang_index_shows_staf_count(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Humas']);

        $staf = User::factory()->create();
        $staf->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'jabatan' => 'Ketua',
            'is_inti' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.bidang.index'));

        $response->assertOk();
        $response->assertSee('Humas');
        $response->assertSee('1 staf');
        $response->assertSee('Tidak bisa dihapus: masih dipakai 1 staf.');
    }
}
