<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/staf/dashboard')->assertRedirect(route('login'));
    }

    public function test_staf_can_open_staf_area(): void
    {
        $staf = User::factory()->create();

        $this->actingAs($staf)->get('/staf/dashboard')->assertOk();
        $this->actingAs($staf)->get('/staf')->assertRedirect(route('staf.dashboard'));
    }

    public function test_staf_can_not_open_admin_area(): void
    {
        $staf = User::factory()->create();

        $this->actingAs($staf)->get('/admin')->assertForbidden();
        $this->actingAs($staf)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_admin_can_open_admin_area(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
        $this->actingAs($admin)->get('/admin')->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_not_open_staf_area(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/staf')->assertForbidden();
        $this->actingAs($admin)->get('/staf/dashboard')->assertForbidden();
    }

    public function test_dashboard_route_follows_the_role(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertRedirect(route('staf.dashboard'));

        $this->actingAs(User::factory()->admin()->create())
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_logged_in_pending_user_is_forced_out(): void
    {
        $pending = User::factory()->pending()->create();

        $response = $this->actingAs($pending)->get('/staf/dashboard');

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logged_in_rejected_user_is_forced_out(): void
    {
        $rejected = User::factory()->rejected()->create();

        $response = $this->actingAs($rejected)->get('/dashboard');

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
