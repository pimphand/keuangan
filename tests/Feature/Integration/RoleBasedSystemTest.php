<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolePermissionSeeder;

class RoleBasedSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run the seeder to create roles and permissions
        $this->seed(RolePermissionSeeder::class);
    }

    /** @test */
    public function complete_role_based_access_system_works()
    {
        // Create users with different roles
        $admin = User::factory()->create();
        $manager = User::factory()->create();
        $bendahara = User::factory()->create();
        $pegawai = User::factory()->create();

        // Assign roles
        $admin->assignRole(Role::where('name', 'Admin')->first());
        $manager->assignRole(Role::where('name', 'Manager')->first());
        $bendahara->assignRole(Role::where('name', 'Bendahara')->first());
        $pegawai->assignRole(Role::where('name', 'Pegawai')->first());

        // Test admin access
        $this->actingAs($admin);
        $this->get('/')->assertRedirect(route('home'));
        $this->get('/home')->assertStatus(200);
        $this->get('/kategori')->assertStatus(200);
        $this->get('/pegawai/beranda')->assertStatus(200);

        // Test manager access
        $this->actingAs($manager);
        $this->get('/')->assertRedirect(route('home'));
        $this->get('/home')->assertStatus(200);
        $this->get('/kategori')->assertStatus(200);
        $this->get('/pegawai/beranda')->assertStatus(200);

        // Test bendahara access
        $this->actingAs($bendahara);
        $this->get('/')->assertRedirect(route('home'));
        $this->get('/home')->assertStatus(200);
        $this->get('/kategori')->assertStatus(200);
        $this->get('/pegawai/beranda')->assertStatus(200);

        // Test pegawai access
        $this->actingAs($pegawai);
        $this->get('/home')->assertRedirect(route('pegawai.beranda'));
        $this->get('/kategori')->assertRedirect(route('pegawai.beranda'));
        $this->get('/pegawai/beranda')->assertStatus(200);
    }

    /** @test */
    public function role_permissions_are_correctly_assigned()
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $bendaharaRole = Role::where('name', 'Bendahara')->first();
        $pegawaiRole = Role::where('name', 'Pegawai')->first();

        // Admin should have all permissions
        $this->assertTrue($adminRole->permissions->count() > 0);

        // Manager should have specific permissions
        $this->assertTrue($managerRole->hasPermission('user.view'));
        $this->assertTrue($managerRole->hasPermission('transaksi.view'));
        $this->assertTrue($managerRole->hasPermission('laporan.view'));

        // Bendahara should have financial permissions
        $this->assertTrue($bendaharaRole->hasPermission('transaksi.view'));
        $this->assertTrue($bendaharaRole->hasPermission('laporan.view'));
        $this->assertTrue($bendaharaRole->hasPermission('kasbon.approve'));

        // Pegawai should have limited permissions
        $this->assertTrue($pegawaiRole->hasPermission('pengumuman.view'));
        $this->assertTrue($pegawaiRole->hasPermission('kasbon.view'));
    }

    /** @test */
    public function user_permissions_work_through_roles()
    {
        $admin = User::factory()->create();
        $pegawai = User::factory()->create();

        $admin->assignRole(Role::where('name', 'Admin')->first());
        $pegawai->assignRole(Role::where('name', 'Pegawai')->first());

        // Admin should have admin permissions
        $this->assertTrue($admin->hasPermission('user.view'));
        $this->assertTrue($admin->hasPermission('transaksi.view'));
        $this->assertTrue($admin->hasPermission('laporan.view'));

        // Pegawai should have limited permissions
        $this->assertFalse($pegawai->hasPermission('user.view'));
        $this->assertFalse($pegawai->hasPermission('transaksi.view'));
        $this->assertTrue($pegawai->hasPermission('pengumuman.view'));
    }

    /** @test */
    public function middleware_blocks_unauthorized_access()
    {
        $pegawai = User::factory()->create();
        $pegawai->assignRole(Role::where('name', 'Pegawai')->first());

        // Pegawai should be blocked from admin routes
        $adminRoutes = [
            '/kategori',
            '/transaksi',
            '/pengguna',
            '/role',
            '/permission',
            '/laporan',
            '/absensi-admin',
            '/kasbon'
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($pegawai)->get($route);
            $response->assertRedirect(route('pegawai.beranda'));
        }
    }

    /** @test */
    public function role_assignment_affects_access_immediately()
    {
        $user = User::factory()->create();

        // Initially no role - should be redirected
        $this->actingAs($user);
        $this->get('/kategori')->assertRedirect(route('home'));
        $this->get('/home')->assertSessionHas('error');

        // Assign admin role
        $user->assignRole(Role::where('name', 'Admin')->first());

        // Now should have access
        $this->get('/kategori')->assertStatus(200);
        $this->get('/home')->assertStatus(200);

        // Change to pegawai role
        $user->syncRoles([Role::where('name', 'Pegawai')->first()->id]);

        // Should be redirected to beranda
        $this->get('/kategori')->assertRedirect(route('pegawai.beranda'));
        $this->get('/home')->assertRedirect(route('pegawai.beranda'));
    }

    /** @test */
    public function multiple_roles_work_correctly()
    {
        $user = User::factory()->create();
        $user->assignRole(Role::where('name', 'Admin')->first());
        $user->assignRole(Role::where('name', 'Pegawai')->first());

        // Should have admin access (admin role takes precedence)
        $this->actingAs($user);
        $this->get('/kategori')->assertStatus(200);
        $this->get('/pegawai/beranda')->assertStatus(200);

        // Should have both role permissions
        $this->assertTrue($user->hasRole('Admin'));
        $this->assertTrue($user->hasRole('Pegawai'));
        $this->assertTrue($user->hasAnyRole(['Admin', 'Pegawai']));
    }
}
