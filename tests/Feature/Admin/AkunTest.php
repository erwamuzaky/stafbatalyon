<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AkunTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_pending_account(): void
    {
        $admin = User::factory()->admin()->create();
        $pending = User::factory()->pending()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.akun.index'))
            ->patch(route('admin.akun.approve', $pending));

        $response->assertRedirect(route('admin.akun.index'));
        $response->assertSessionHas('success');
        $this->assertSame('approved', $pending->fresh()->status);
    }

    public function test_admin_can_reject_pending_account(): void
    {
        $admin = User::factory()->admin()->create();
        $pending = User::factory()->pending()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.akun.index'))
            ->patch(route('admin.akun.reject', $pending));

        $response->assertRedirect(route('admin.akun.index'));
        $response->assertSessionHas('success');
        $this->assertSame('rejected', $pending->fresh()->status);
    }

    public function test_admin_can_deactivate_approved_account(): void
    {
        $admin = User::factory()->admin()->create();
        $staf = User::factory()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.akun.index'))
            ->patch(route('admin.akun.deactivate', $staf));

        $response->assertRedirect(route('admin.akun.index'));
        $response->assertSessionHas('success');
        $this->assertSame('pending', $staf->fresh()->status);
    }

    public function test_admin_cannot_deactivate_its_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.akun.index'))
            ->patch(route('admin.akun.deactivate', $admin));

        $response->assertRedirect(route('admin.akun.index'));
        $response->assertSessionHas('error');
        $this->assertSame('approved', $admin->fresh()->status);
    }

    public function test_admin_cannot_delete_its_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.akun.index'))
            ->delete(route('admin.akun.destroy', $admin));

        $response->assertRedirect(route('admin.akun.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_another_account(): void
    {
        $admin = User::factory()->admin()->create();
        $lain = User::factory()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.akun.index'))
            ->delete(route('admin.akun.destroy', $lain));

        $response->assertRedirect(route('admin.akun.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $lain->id]);
    }

    public function test_admin_can_reset_password_and_it_is_shown_once(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();
        $hashLama = $target->password;

        $response = $this->actingAs($admin)
            ->from(route('admin.akun.index'))
            ->post(route('admin.akun.reset-password', $target));

        $response->assertRedirect(route('admin.akun.index'));
        $response->assertSessionHas('password_baru');
        $response->assertSessionHas('success');

        $flash = session('password_baru');
        $this->assertIsArray($flash);
        $this->assertSame($target->email, $flash['email']);

        $hashBaru = $target->fresh()->password;
        $this->assertNotSame($hashLama, $hashBaru);
        $this->assertTrue(Hash::check($flash['password'], $hashBaru));
    }

    public function test_generated_password_banner_is_shown_only_once(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.akun.index'))
            ->post(route('admin.akun.reset-password', $target));

        $this->actingAs($admin)
            ->get(route('admin.akun.index'))
            ->assertSee('Kata sandi baru sudah dibuat');

        $this->actingAs($admin)
            ->get(route('admin.akun.index'))
            ->assertDontSee('Kata sandi baru sudah dibuat');
    }

    public function test_akun_index_can_be_filtered_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        $menunggu = User::factory()->pending()->create(['name' => 'Menunggu Selamanya']);
        $disetujui = User::factory()->create(['name' => 'Sudah Disetujui']);

        $response = $this->actingAs($admin)->get(route('admin.akun.index', ['status' => 'pending']));

        $response->assertOk();
        $response->assertSee('Menunggu Selamanya');
        $response->assertDontSee('Sudah Disetujui');
    }

    public function test_akun_index_can_be_searched_by_name_or_email(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->pending()->create(['name' => 'Cari Aku Sendiri', 'email' => 'cari@example.test']);
        User::factory()->pending()->create(['name' => 'Bukan Target', 'email' => 'lain@example.test']);

        $response = $this->actingAs($admin)->get(route('admin.akun.index', ['q' => 'cari aku']));

        $response->assertOk();
        $response->assertSee('Cari Aku Sendiri');
        $response->assertDontSee('Bukan Target');

        $responseEmail = $this->actingAs($admin)->get(route('admin.akun.index', ['q' => 'lain@example']));
        $responseEmail->assertOk();
        $responseEmail->assertSee('Bukan Target');
        $responseEmail->assertDontSee('Cari Aku Sendiri');
    }
}
