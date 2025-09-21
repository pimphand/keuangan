<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole;
    protected $managerRole;
    protected $pegawaiRole;
    protected $userViewPermission;
    protected $userCreatePermission;
    protected $transaksiViewPermission;

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

        $this->pegawaiRole = Role::create([
            'name' => 'Pegawai',
            'display_name' => 'Pegawai',
            'description' => 'Employee access',
            'is_active' => true
        ]);

        // Create permissions
        $this->userViewPermission = Permission::create([
            'name' => 'user.view',
            'display_name' => 'Lihat Pengguna',
            'group' => 'user',
            'description' => 'Dapat melihat daftar pengguna'
        ]);

        $this->userCreatePermission = Permission::create([
            'name' => 'user.create',
            'display_name' => 'Tambah Pengguna',
            'group' => 'user',
            'description' => 'Dapat menambah pengguna baru'
        ]);

        $this->transaksiViewPermission = Permission::create([
            'name' => 'transaksi.view',
            'display_name' => 'Lihat Transaksi',
            'group' => 'transaksi',
            'description' => 'Dapat melihat daftar transaksi'
        ]);
    }

    /** @test */
    public function user_can_have_role_assigned()
    {
        $user = User::factory()->create();

        $user->assignRole($this->adminRole);

        $this->assertTrue($user->hasRole('Admin'));
        $this->assertTrue($user->roles->contains($this->adminRole));
    }

    /** @test */
    public function user_can_have_multiple_roles()
    {
        $user = User::factory()->create();

        $user->assignRole($this->adminRole);
        $user->assignRole($this->managerRole);

        $this->assertTrue($user->hasRole('Admin'));
        $this->assertTrue($user->hasRole('Manager'));
        $this->assertTrue($user->hasAnyRole(['Admin', 'Manager']));
    }

    /** @test */
    public function user_can_have_role_removed()
    {
        $user = User::factory()->create();
        $user->assignRole($this->adminRole);

        $this->assertTrue($user->hasRole('Admin'));

        $user->removeRole($this->adminRole);

        $this->assertFalse($user->hasRole('Admin'));
    }

    /** @test */
    public function user_roles_can_be_synced()
    {
        $user = User::factory()->create();
        $user->assignRole($this->adminRole);

        $user->syncRoles([$this->managerRole->id, $this->pegawaiRole->id]);

        $this->assertFalse($user->hasRole('Admin'));
        $this->assertTrue($user->hasRole('Manager'));
        $this->assertTrue($user->hasRole('Pegawai'));
    }

    /** @test */
    public function role_can_have_permissions_assigned()
    {
        $this->adminRole->givePermissionTo($this->userViewPermission);
        $this->adminRole->givePermissionTo($this->userCreatePermission);

        $this->assertTrue($this->adminRole->hasPermission('user.view'));
        $this->assertTrue($this->adminRole->hasPermission('user.create'));
    }

    /** @test */
    public function role_permissions_can_be_synced()
    {
        $this->adminRole->permissions()->sync([
            $this->userViewPermission->id,
            $this->transaksiViewPermission->id
        ]);

        $this->assertTrue($this->adminRole->hasPermission('user.view'));
        $this->assertTrue($this->adminRole->hasPermission('transaksi.view'));
        $this->assertFalse($this->adminRole->hasPermission('user.create'));
    }

    /** @test */
    public function user_can_have_permission_through_role()
    {
        $user = User::factory()->create();
        $user->assignRole($this->adminRole);

        $this->adminRole->givePermissionTo($this->userViewPermission);

        $this->assertTrue($user->hasPermission('user.view'));
    }

    /** @test */
    public function user_without_role_has_no_permissions()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->hasPermission('user.view'));
        $this->assertFalse($user->hasRole('Admin'));
    }

    /** @test */
    public function role_can_have_permission_revoked()
    {
        $this->adminRole->givePermissionTo($this->userViewPermission);
        $this->assertTrue($this->adminRole->hasPermission('user.view'));

        $this->adminRole->revokePermissionTo($this->userViewPermission);
        $this->assertFalse($this->adminRole->hasPermission('user.view'));
    }

    /** @test */
    public function user_has_any_role_works_correctly()
    {
        $user = User::factory()->create();
        $user->assignRole($this->managerRole);

        $this->assertTrue($user->hasAnyRole(['Admin', 'Manager']));
        $this->assertFalse($user->hasAnyRole(['Admin', 'Pegawai']));
    }

    /** @test */
    public function role_scope_active_works()
    {
        $activeRole = Role::create([
            'name' => 'ActiveRole',
            'display_name' => 'Active Role',
            'description' => 'Active role',
            'is_active' => true
        ]);

        $inactiveRole = Role::create([
            'name' => 'InactiveRole',
            'display_name' => 'Inactive Role',
            'description' => 'Inactive role',
            'is_active' => false
        ]);

        $activeRoles = Role::active()->get();

        $this->assertTrue($activeRoles->contains($activeRole));
        $this->assertFalse($activeRoles->contains($inactiveRole));
    }
}
