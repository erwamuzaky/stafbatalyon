<?php

namespace Tests\Feature\Admin;

use App\Models\Bidang;
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
     * Guest diarahkan ke halaman masuk, bukan halaman admin.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }
}
