<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StafTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_staf_manually(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Sekretaris']);

        $response = $this->actingAs($admin)->post(route('admin.staf.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'bidang_id' => $bidang->id,
            'jabatan' => 'Ketua Bidang',
            'is_inti' => '1',
            'no_hp' => '081234567890',
        ]);

        $response->assertRedirect(route('admin.staf.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.test',
            'role' => 'staf',
            'status' => 'approved',
        ]);

        $staf = User::where('email', 'budi@example.test')->firstOrFail();

        $this->assertNotNull($staf->stafProfile);
        $this->assertSame($bidang->id, $staf->stafProfile->bidang_id);
        $this->assertSame('Ketua Bidang', $staf->stafProfile->jabatan);
        $this->assertTrue($staf->stafProfile->is_inti);
    }

    public function test_staf_store_requires_bidang(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.staf.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertSessionHasErrors('bidang_id');
        $this->assertDatabaseMissing('users', ['email' => 'budi@example.test']);
    }

    public function test_admin_can_update_staf_profile(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Sekretaris']);
        $bidangBaru = Bidang::create(['nama' => 'Humas']);

        $staf = User::factory()->create();
        $staf->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'jabatan' => 'Anggota',
            'is_inti' => true,
            'no_hp' => '0811000',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.staf.update', $staf), [
            'bidang_id' => $bidangBaru->id,
            'jabatan' => 'Wakil',
            'is_inti' => '0',
            'no_hp' => '0822000',
        ]);

        $response->assertRedirect(route('admin.staf.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('staf_profiles', [
            'user_id' => $staf->id,
            'bidang_id' => $bidangBaru->id,
            'jabatan' => 'Wakil',
            'is_inti' => 0,
            'no_hp' => '0822000',
        ]);
    }

    public function test_admin_can_open_edit_form(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.staf.edit', $staf))
            ->assertOk()
            ->assertSee($staf->email);
    }

    public function test_admin_cannot_edit_admin_account_from_staf_page(): void
    {
        $admin = User::factory()->admin()->create();
        $adminLain = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.staf.edit', $adminLain))->assertNotFound();
    }

    public function test_admin_can_delete_staf(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Sekretaris']);

        $staf = User::factory()->create();
        $staf->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'is_inti' => false,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.staf.index'))
            ->delete(route('admin.staf.destroy', $staf));

        $response->assertRedirect(route('admin.staf.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $staf->id]);
        $this->assertDatabaseMissing('staf_profiles', ['user_id' => $staf->id]);
    }

    public function test_admin_cannot_delete_its_own_account_from_staf_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.staf.index'))
            ->delete(route('admin.staf.destroy', $admin));

        $response->assertRedirect(route('admin.staf.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_delete_another_admin_from_staf_page(): void
    {
        $admin = User::factory()->admin()->create();
        $adminLain = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.staf.index'))
            ->delete(route('admin.staf.destroy', $adminLain));

        $response->assertRedirect(route('admin.staf.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $adminLain->id]);
    }

    public function test_staf_index_shows_staf_data(): void
    {
        $admin = User::factory()->admin()->create();
        $bidang = Bidang::create(['nama' => 'Sekretaris']);

        $staf = User::factory()->create(['name' => 'Siti Aminah']);
        $staf->stafProfile()->create([
            'bidang_id' => $bidang->id,
            'jabatan' => 'Sekretaris Umum',
            'is_inti' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.staf.index'));

        $response->assertOk();
        $response->assertSee('Siti Aminah');
        $response->assertSee($staf->email);
        $response->assertSee('Sekretaris');
        $response->assertSee('Inti');
    }
}
