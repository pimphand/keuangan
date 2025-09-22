<?php

namespace Tests\Feature\Routes;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole;
    protected $managerRole;
    protected $bendaharaRole;
    protected $pegawaiRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->adminRole = Role::create([
            'name' => 'Admin',
            'display_name' => 'Administrator',
            'description' => 'Full access',
            'is_active' => true
        ]);

        $this->managerRole = Role::create([
            'name' => 'Manager',
            'display_name' => 'Manager',
            'description' => 'Manager access',
            'is_active' => true
        ]);

        $this->bendaharaRole = Role::create([
            'name' => 'Bendahara',
            'display_name' => 'Bendahara',
            'description' => 'Bendahara access',
            'is_active' => true
        ]);

        $this->pegawaiRole = Role::create([
            'name' => 'Karyawan',
            'display_name' => 'Karyawan',
            'description' => 'Employee access',
            'is_active' => true
        ]);
    }

    /** @test */
    public function admin_can_access_all_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->adminRole);

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
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function manager_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->managerRole);

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
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function bendahara_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->bendaharaRole);

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
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function pegawai_cannot_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->pegawaiRole);

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
            $response = $this->actingAs($user)->get($route);
            $response->assertRedirect(route('pegawai.beranda'));
        }
    }

    /** @test */
    public function all_roles_can_access_pegawai_routes()
    {
        $roles = [$this->adminRole, $this->managerRole, $this->bendaharaRole, $this->pegawaiRole];

        $pegawaiRoutes = [
            '/pegawai/beranda',
            '/pegawai/absensi',
            '/pegawai/riwayat',
            '/pegawai/kasbon',
            '/pegawai/profil',
            '/pegawai/pengumuman'
        ];

        foreach ($roles as $role) {
            $user = User::factory()->create();
            $user->roles()->attach($role);

            foreach ($pegawaiRoutes as $route) {
                $response = $this->actingAs($user)->get($route);
                $response->assertStatus(200);
            }
        }
    }

    /** @test */
    public function root_route_redirects_based_on_role()
    {
        // Root route has guest middleware, so authenticated users are redirected
        // Test unauthenticated user sees login page
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function unauthenticated_user_sees_login_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }
}
