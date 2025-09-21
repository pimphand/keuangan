<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class PegawaiRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole;
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

        $this->pegawaiRole = Role::create([
            'name' => 'Pegawai',
            'display_name' => 'Pegawai',
            'description' => 'Employee access',
            'is_active' => true
        ]);

        // Create test route with pegawai.redirect middleware
        Route::middleware(['auth', 'pegawai.redirect'])->get('/test-pegawai-redirect', function () {
            return response()->json(['message' => 'Access granted']);
        });
    }

    /** @test */
    public function pegawai_redirected_to_beranda()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->pegawaiRole);

        $response = $this->actingAs($user)->get('/test-pegawai-redirect');

        $response->assertRedirect(route('pegawai.beranda'));
    }

    /** @test */
    public function admin_can_access_route_without_redirect()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->adminRole);

        $response = $this->actingAs($user)->get('/test-pegawai-redirect');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Access granted']);
    }

    /** @test */
    public function unauthenticated_user_redirected_to_login()
    {
        $response = $this->get('/test-pegawai-redirect');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_without_role_can_access_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/test-pegawai-redirect');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Access granted']);
    }
}
