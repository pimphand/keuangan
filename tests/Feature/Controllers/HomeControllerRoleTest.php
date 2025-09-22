<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerRoleTest extends TestCase
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
    public function pegawai_redirected_to_beranda_from_home()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->pegawaiRole);

        $response = $this->actingAs($user)->get('/home');

        $response->assertRedirect(route('pegawai.beranda'));
    }

    /** @test */
    public function admin_can_access_home_dashboard()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->adminRole);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('app.index');
    }

    /** @test */
    public function manager_can_access_home_dashboard()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->managerRole);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('app.index');
    }

    /** @test */
    public function bendahara_can_access_home_dashboard()
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->bendaharaRole);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('app.index');
    }

    /** @test */
    public function user_with_multiple_roles_including_pegawai_redirected_to_beranda()
    {
        $user = User::factory()->create();
        $user->roles()->attach([$this->pegawaiRole->id, $this->adminRole->id]);

        $response = $this->actingAs($user)->get('/home');

        $response->assertRedirect(route('pegawai.beranda'));
    }

    /** @test */
    public function unauthenticated_user_redirected_to_login()
    {
        $response = $this->get('/home');

        $response->assertRedirect(route('login'));
    }
}
