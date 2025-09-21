<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class AdminAccessTest extends TestCase
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
            'name' => 'Pegawai',
            'display_name' => 'Pegawai',
            'description' => 'Employee access',
            'is_active' => true
        ]);

        // Create test route with admin.access middleware
        Route::middleware(['auth', 'admin.access'])->get('/test-admin', function () {
            return response()->json(['message' => 'Admin access granted']);
        });
    }

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->adminRole);

        $response = $this->actingAs($user)->get('/test-admin');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Admin access granted']);
    }

    /** @test */
    public function manager_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->managerRole);

        $response = $this->actingAs($user)->get('/test-admin');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Admin access granted']);
    }

    /** @test */
    public function bendahara_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->bendaharaRole);

        $response = $this->actingAs($user)->get('/test-admin');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Admin access granted']);
    }

    /** @test */
    public function pegawai_redirected_to_beranda_when_accessing_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->pegawaiRole);

        $response = $this->actingAs($user)->get('/test-admin');

        $response->assertRedirect(route('pegawai.beranda'));
    }

    /** @test */
    public function user_without_role_redirected_to_home_with_error()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/test-admin');

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    /** @test */
    public function unauthenticated_user_redirected_to_login()
    {
        $response = $this->get('/test-admin');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_with_multiple_roles_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->roles()->attach([$this->pegawaiRole->id, $this->adminRole->id]);

        $response = $this->actingAs($user)->get('/test-admin');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Admin access granted']);
    }
}
