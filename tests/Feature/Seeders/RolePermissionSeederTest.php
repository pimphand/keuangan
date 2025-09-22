<?php

namespace Tests\Feature\Seeders;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolePermissionSeeder;

class RolePermissionSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeder_creates_all_required_roles()
    {
        $this->seed(RolePermissionSeeder::class);

        $expectedRoles = ['Admin', 'Manager', 'Bendahara', 'Karyawan'];

        foreach ($expectedRoles as $roleName) {
            $this->assertDatabaseHas('roles', [
                'name' => $roleName,
                'is_active' => true
            ]);
        }
    }

    /** @test */
    public function seeder_creates_all_required_permissions()
    {
        $this->seed(RolePermissionSeeder::class);

        $expectedPermissions = [
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
            'permission.view',
            'permission.create',
            'permission.edit',
            'permission.delete',
            'transaksi.view',
            'transaksi.create',
            'transaksi.edit',
            'transaksi.delete',
            'kategori.view',
            'kategori.create',
            'kategori.edit',
            'kategori.delete',
            'laporan.view',
            'laporan.export',
            'pegawai.view',
            'pegawai.create',
            'pegawai.edit',
            'pegawai.delete',
            'absensi.view',
            'absensi.manage',
            'kasbon.view',
            'kasbon.approve',
            'kasbon.reject',
            'pengumuman.view',
            'pengumuman.create',
            'pengumuman.edit',
            'pengumuman.delete'
        ];

        foreach ($expectedPermissions as $permissionName) {
            $this->assertDatabaseHas('permissions', [
                'name' => $permissionName
            ]);
        }
    }

    /** @test */
    public function admin_role_has_all_permissions()
    {
        $this->seed(RolePermissionSeeder::class);

        $adminRole = Role::where('name', 'Admin')->first();
        $allPermissions = Permission::all();

        $this->assertEquals($allPermissions->count(), $adminRole->permissions->count());

        foreach ($allPermissions as $permission) {
            $this->assertTrue($adminRole->hasPermission($permission->name));
        }
    }

    /** @test */
    public function manager_role_has_correct_permissions()
    {
        $this->seed(RolePermissionSeeder::class);

        $managerRole = Role::where('name', 'Manager')->first();

        $expectedPermissions = [
            'user.view',
            'transaksi.view',
            'transaksi.create',
            'transaksi.edit',
            'kategori.view',
            'kategori.create',
            'kategori.edit',
            'laporan.view',
            'laporan.export',
            'pegawai.view',
            'absensi.view',
            'kasbon.view',
            'kasbon.approve',
            'kasbon.reject',
            'pengumuman.view',
            'pengumuman.create',
            'pengumuman.edit'
        ];

        foreach ($expectedPermissions as $permission) {
            $this->assertTrue($managerRole->hasPermission($permission), "Manager should have {$permission} permission");
        }

        // Manager should not have delete permissions
        $this->assertFalse($managerRole->hasPermission('user.delete'));
        $this->assertFalse($managerRole->hasPermission('role.delete'));
        $this->assertFalse($managerRole->hasPermission('permission.delete'));
    }

    /** @test */
    public function bendahara_role_has_correct_permissions()
    {
        $this->seed(RolePermissionSeeder::class);

        $bendaharaRole = Role::where('name', 'Bendahara')->first();

        $expectedPermissions = [
            'transaksi.view',
            'transaksi.create',
            'transaksi.edit',
            'kategori.view',
            'kategori.create',
            'kategori.edit',
            'laporan.view',
            'laporan.export',
            'kasbon.view',
            'kasbon.approve',
            'kasbon.reject',
            'pengumuman.view'
        ];

        foreach ($expectedPermissions as $permission) {
            $this->assertTrue($bendaharaRole->hasPermission($permission), "Bendahara should have {$permission} permission");
        }

        // Bendahara should not have user management permissions
        $this->assertFalse($bendaharaRole->hasPermission('user.view'));
        $this->assertFalse($bendaharaRole->hasPermission('pegawai.view'));
    }

    /** @test */
    public function pegawai_role_has_limited_permissions()
    {
        $this->seed(RolePermissionSeeder::class);

        $pegawaiRole = Role::where('name', 'Karyawan')->first();

        $expectedPermissions = [
            'pengumuman.view',
            'kasbon.view'
        ];

        foreach ($expectedPermissions as $permission) {
            $this->assertTrue($pegawaiRole->hasPermission($permission), "Pegawai should have {$permission} permission");
        }

        // Pegawai should not have admin permissions
        $this->assertFalse($pegawaiRole->hasPermission('user.view'));
        $this->assertFalse($pegawaiRole->hasPermission('transaksi.view'));
        $this->assertFalse($pegawaiRole->hasPermission('laporan.view'));
    }

    /** @test */
    public function seeder_can_be_run_multiple_times()
    {
        // Run seeder first time
        $this->seed(RolePermissionSeeder::class);
        $firstRunRoleCount = Role::count();
        $firstRunPermissionCount = Permission::count();

        // Run seeder second time
        $this->seed(RolePermissionSeeder::class);
        $secondRunRoleCount = Role::count();
        $secondRunPermissionCount = Permission::count();

        // Should not create duplicates
        $this->assertEquals($firstRunRoleCount, $secondRunRoleCount);
        $this->assertEquals($firstRunPermissionCount, $secondRunPermissionCount);
    }
}
