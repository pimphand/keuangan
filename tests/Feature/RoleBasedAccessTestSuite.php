<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolePermissionSeeder;

/**
 * Complete test suite for role-based access control system
 *
 * This test suite covers:
 * - Middleware functionality
 * - Controller redirects
 * - Route access control
 * - Role and permission management
 * - Integration testing
 */
class RoleBasedAccessTestSuite extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /** @test */
    public function complete_role_based_system_integration()
    {
        // This test runs all the key scenarios in one comprehensive test

        // 1. Test role creation and assignment
        $admin = \App\Models\User::factory()->create();
        $manager = \App\Models\User::factory()->create();
        $bendahara = \App\Models\User::factory()->create();
        $pegawai = \App\Models\User::factory()->create();

        $admin->assignRole(\App\Models\Role::where('name', 'Admin')->first());
        $manager->assignRole(\App\Models\Role::where('name', 'Manager')->first());
        $bendahara->assignRole(\App\Models\Role::where('name', 'Bendahara')->first());
        $pegawai->assignRole(\App\Models\Role::where('name', 'Pegawai')->first());

        // 2. Test root route redirects (only works for unauthenticated users due to guest middleware)
        // For authenticated users, we test the home route instead
        $this->actingAs($admin)->get('/home')->assertStatus(200);
        $this->actingAs($manager)->get('/home')->assertStatus(200);
        $this->actingAs($bendahara)->get('/home')->assertStatus(200);
        $this->actingAs($pegawai)->get('/home')->assertRedirect(route('pegawai.beranda'));

        // 3. Test home controller redirects
        $this->actingAs($admin)->get('/home')->assertStatus(200);
        $this->actingAs($manager)->get('/home')->assertStatus(200);
        $this->actingAs($bendahara)->get('/home')->assertStatus(200);
        $this->actingAs($pegawai)->get('/home')->assertRedirect(route('pegawai.beranda'));

        // 4. Test admin route access
        $adminRoutes = ['/kategori', '/transaksi', '/pengguna', '/role', '/permission', '/laporan'];

        foreach ($adminRoutes as $route) {
            $this->actingAs($admin)->get($route)->assertStatus(200);
            $this->actingAs($manager)->get($route)->assertStatus(200);
            $this->actingAs($bendahara)->get($route)->assertStatus(200);
            $this->actingAs($pegawai)->get($route)->assertRedirect(route('pegawai.beranda'));
        }

        // 5. Test pegawai route access (all roles can access)
        $pegawaiRoutes = ['/pegawai/beranda', '/pegawai/absensi', '/pegawai/profil'];

        foreach ($pegawaiRoutes as $route) {
            $this->actingAs($admin)->get($route)->assertStatus(200);
            $this->actingAs($manager)->get($route)->assertStatus(200);
            $this->actingAs($bendahara)->get($route)->assertStatus(200);
            $this->actingAs($pegawai)->get($route)->assertStatus(200);
        }

        // 6. Test role permissions
        $this->assertTrue($admin->hasPermission('user.view'));
        $this->assertTrue($manager->hasPermission('user.view'));
        $this->assertFalse($bendahara->hasPermission('user.view'));
        $this->assertFalse($pegawai->hasPermission('user.view'));

        $this->assertTrue($admin->hasPermission('transaksi.view'));
        $this->assertTrue($manager->hasPermission('transaksi.view'));
        $this->assertTrue($bendahara->hasPermission('transaksi.view'));
        $this->assertFalse($pegawai->hasPermission('transaksi.view'));

        // 7. Test role checks
        $this->assertTrue($admin->hasRole('Admin'));
        $this->assertTrue($manager->hasRole('Manager'));
        $this->assertTrue($bendahara->hasRole('Bendahara'));
        $this->assertTrue($pegawai->hasRole('Pegawai'));

        $this->assertTrue($admin->hasAnyRole(['Admin', 'Manager']));
        $this->assertFalse($pegawai->hasAnyRole(['Admin', 'Manager']));
    }

    /** @test */
    public function middleware_blocks_unauthorized_access_properly()
    {
        $pegawai = \App\Models\User::factory()->create();
        $pegawai->assignRole(\App\Models\Role::where('name', 'Pegawai')->first());

        // Test that pegawai is blocked from admin routes
        $response = $this->actingAs($pegawai)->get('/kategori');
        $response->assertRedirect(route('pegawai.beranda'));

        $response = $this->actingAs($pegawai)->get('/transaksi');
        $response->assertRedirect(route('pegawai.beranda'));

        $response = $this->actingAs($pegawai)->get('/pengguna');
        $response->assertRedirect(route('pegawai.beranda'));
    }

    /** @test */
    public function role_changes_affect_access_immediately()
    {
        $user = \App\Models\User::factory()->create();

        // Initially no role
        $this->actingAs($user);
        $this->get('/kategori')->assertRedirect(route('home'));
        $this->get('/home')->assertSessionHas('error');

        // Assign admin role
        $user->assignRole(\App\Models\Role::where('name', 'Admin')->first());
        $this->get('/kategori')->assertStatus(200);
        $this->get('/home')->assertStatus(200);

        // Change to pegawai role
        $user->syncRoles([\App\Models\Role::where('name', 'Pegawai')->first()->id]);
        $this->get('/kategori')->assertRedirect(route('pegawai.beranda'));
        $this->get('/home')->assertRedirect(route('pegawai.beranda'));
    }

    /** @test */
    public function unauthenticated_users_are_handled_correctly()
    {
        // Root route should show login for unauthenticated users
        $this->get('/')->assertStatus(200)->assertViewIs('auth.login');

        // Admin routes should redirect to login
        $this->get('/kategori')->assertRedirect(route('login'));
        $this->get('/home')->assertRedirect(route('login'));

        // Pegawai routes should redirect to login
        $this->get('/pegawai/beranda')->assertRedirect(route('login'));
    }
}
