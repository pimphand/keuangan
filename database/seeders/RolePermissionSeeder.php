<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create comprehensive permissions for all current pages
        $permissions = [
            // Dashboard/Home
            ['name' => 'dashboard.view', 'display_name' => 'Lihat Dashboard', 'group' => 'dashboard', 'description' => 'Dapat mengakses dashboard utama'],

            // User Management
            ['name' => 'user.view', 'display_name' => 'Lihat Pengguna', 'group' => 'user', 'description' => 'Dapat melihat daftar pengguna'],
            ['name' => 'user.create', 'display_name' => 'Tambah Pengguna', 'group' => 'user', 'description' => 'Dapat menambah pengguna baru'],
            ['name' => 'user.edit', 'display_name' => 'Edit Pengguna', 'group' => 'user', 'description' => 'Dapat mengedit data pengguna'],
            ['name' => 'user.delete', 'display_name' => 'Hapus Pengguna', 'group' => 'user', 'description' => 'Dapat menghapus pengguna'],
            ['name' => 'user.add', 'display_name' => 'Tambah Pengguna Form', 'group' => 'user', 'description' => 'Dapat mengakses form tambah pengguna'],
            ['name' => 'user.action', 'display_name' => 'Aksi Pengguna', 'group' => 'user', 'description' => 'Dapat melakukan aksi pada pengguna'],
            ['name' => 'user.update', 'display_name' => 'Update Pengguna', 'group' => 'user', 'description' => 'Dapat update data pengguna'],

            // Role Management
            ['name' => 'role.view', 'display_name' => 'Lihat Role', 'group' => 'role', 'description' => 'Dapat melihat daftar role'],
            ['name' => 'role.create', 'display_name' => 'Tambah Role', 'group' => 'role', 'description' => 'Dapat menambah role baru'],
            ['name' => 'role.edit', 'display_name' => 'Edit Role', 'group' => 'role', 'description' => 'Dapat mengedit role'],
            ['name' => 'role.delete', 'display_name' => 'Hapus Role', 'group' => 'role', 'description' => 'Dapat menghapus role'],
            ['name' => 'role.store', 'display_name' => 'Simpan Role', 'group' => 'role', 'description' => 'Dapat menyimpan role baru'],
            ['name' => 'role.update', 'display_name' => 'Update Role', 'group' => 'role', 'description' => 'Dapat update role'],
            ['name' => 'role.show', 'display_name' => 'Detail Role', 'group' => 'role', 'description' => 'Dapat melihat detail role'],
            ['name' => 'role.destroy', 'display_name' => 'Hapus Role', 'group' => 'role', 'description' => 'Dapat menghapus role'],

            // Permission Management
            ['name' => 'permission.view', 'display_name' => 'Lihat Permission', 'group' => 'permission', 'description' => 'Dapat melihat daftar permission'],
            ['name' => 'permission.create', 'display_name' => 'Tambah Permission', 'group' => 'permission', 'description' => 'Dapat menambah permission baru'],
            ['name' => 'permission.edit', 'display_name' => 'Edit Permission', 'group' => 'permission', 'description' => 'Dapat mengedit permission'],
            ['name' => 'permission.delete', 'display_name' => 'Hapus Permission', 'group' => 'permission', 'description' => 'Dapat menghapus permission'],
            ['name' => 'permission.store', 'display_name' => 'Simpan Permission', 'group' => 'permission', 'description' => 'Dapat menyimpan permission baru'],
            ['name' => 'permission.update', 'display_name' => 'Update Permission', 'group' => 'permission', 'description' => 'Dapat update permission'],
            ['name' => 'permission.show', 'display_name' => 'Detail Permission', 'group' => 'permission', 'description' => 'Dapat melihat detail permission'],
            ['name' => 'permission.destroy', 'display_name' => 'Hapus Permission', 'group' => 'permission', 'description' => 'Dapat menghapus permission'],

            // Category Management
            ['name' => 'kategori.view', 'display_name' => 'Lihat Kategori', 'group' => 'kategori', 'description' => 'Dapat melihat daftar kategori'],
            ['name' => 'kategori.create', 'display_name' => 'Tambah Kategori', 'group' => 'kategori', 'description' => 'Dapat menambah kategori baru'],
            ['name' => 'kategori.edit', 'display_name' => 'Edit Kategori', 'group' => 'kategori', 'description' => 'Dapat mengedit kategori'],
            ['name' => 'kategori.delete', 'display_name' => 'Hapus Kategori', 'group' => 'kategori', 'description' => 'Dapat menghapus kategori'],
            ['name' => 'kategori.action', 'display_name' => 'Aksi Kategori', 'group' => 'kategori', 'description' => 'Dapat melakukan aksi pada kategori'],
            ['name' => 'kategori.import', 'display_name' => 'Import Kategori', 'group' => 'kategori', 'description' => 'Dapat import data kategori'],
            ['name' => 'kategori.template', 'display_name' => 'Template Kategori', 'group' => 'kategori', 'description' => 'Dapat download template kategori'],
            ['name' => 'kategori.update', 'display_name' => 'Update Kategori', 'group' => 'kategori', 'description' => 'Dapat update data kategori'],
            ['name' => 'kategori.destroy', 'display_name' => 'Hapus Kategori', 'group' => 'kategori', 'description' => 'Dapat menghapus kategori'],

            // Transaction Management
            ['name' => 'transaksi.view', 'display_name' => 'Lihat Transaksi', 'group' => 'transaksi', 'description' => 'Dapat melihat daftar transaksi'],
            ['name' => 'transaksi.create', 'display_name' => 'Tambah Transaksi', 'group' => 'transaksi', 'description' => 'Dapat menambah transaksi baru'],
            ['name' => 'transaksi.edit', 'display_name' => 'Edit Transaksi', 'group' => 'transaksi', 'description' => 'Dapat mengedit transaksi'],
            ['name' => 'transaksi.delete', 'display_name' => 'Hapus Transaksi', 'group' => 'transaksi', 'description' => 'Dapat menghapus transaksi'],
            ['name' => 'transaksi.action', 'display_name' => 'Aksi Transaksi', 'group' => 'transaksi', 'description' => 'Dapat melakukan aksi pada transaksi'],
            ['name' => 'transaksi.export', 'display_name' => 'Export Transaksi', 'group' => 'transaksi', 'description' => 'Dapat export data transaksi'],
            ['name' => 'transaksi.template', 'display_name' => 'Template Transaksi', 'group' => 'transaksi', 'description' => 'Dapat download template transaksi'],
            ['name' => 'transaksi.import', 'display_name' => 'Import Transaksi', 'group' => 'transaksi', 'description' => 'Dapat import data transaksi'],
            ['name' => 'transaksi.update', 'display_name' => 'Update Transaksi', 'group' => 'transaksi', 'description' => 'Dapat update data transaksi'],
            ['name' => 'transaksi.destroy', 'display_name' => 'Hapus Transaksi', 'group' => 'transaksi', 'description' => 'Dapat menghapus transaksi'],

            // Password Management
            ['name' => 'password.view', 'display_name' => 'Lihat Password', 'group' => 'password', 'description' => 'Dapat mengakses halaman ganti password'],
            ['name' => 'password.update', 'display_name' => 'Update Password', 'group' => 'password', 'description' => 'Dapat mengubah password'],

            // Report Management
            ['name' => 'laporan.view', 'display_name' => 'Lihat Laporan', 'group' => 'laporan', 'description' => 'Dapat melihat laporan'],
            ['name' => 'laporan.export', 'display_name' => 'Export Laporan', 'group' => 'laporan', 'description' => 'Dapat export laporan'],
            ['name' => 'laporan.pdf', 'display_name' => 'Laporan PDF', 'group' => 'laporan', 'description' => 'Dapat generate laporan PDF'],
            ['name' => 'laporan.print', 'display_name' => 'Print Laporan', 'group' => 'laporan', 'description' => 'Dapat print laporan'],

            // Attendance Management (Admin)
            ['name' => 'absensi.admin.view', 'display_name' => 'Lihat Absensi Admin', 'group' => 'absensi', 'description' => 'Dapat melihat daftar absensi sebagai admin'],
            ['name' => 'absensi.admin.update_status', 'display_name' => 'Update Status Absensi', 'group' => 'absensi', 'description' => 'Dapat mengubah status absensi'],

            // Loan Management (Kasbon)
            ['name' => 'kasbon.view', 'display_name' => 'Lihat Kasbon', 'group' => 'kasbon', 'description' => 'Dapat melihat daftar kasbon'],
            ['name' => 'kasbon.create', 'display_name' => 'Tambah Kasbon', 'group' => 'kasbon', 'description' => 'Dapat menambah kasbon baru'],
            ['name' => 'kasbon.edit', 'display_name' => 'Edit Kasbon', 'group' => 'kasbon', 'description' => 'Dapat mengedit kasbon'],
            ['name' => 'kasbon.delete', 'display_name' => 'Hapus Kasbon', 'group' => 'kasbon', 'description' => 'Dapat menghapus kasbon'],
            ['name' => 'kasbon.show', 'display_name' => 'Detail Kasbon', 'group' => 'kasbon', 'description' => 'Dapat melihat detail kasbon'],
            ['name' => 'kasbon.store', 'display_name' => 'Simpan Kasbon', 'group' => 'kasbon', 'description' => 'Dapat menyimpan kasbon baru'],
            ['name' => 'kasbon.update', 'display_name' => 'Update Kasbon', 'group' => 'kasbon', 'description' => 'Dapat update kasbon'],
            ['name' => 'kasbon.destroy', 'display_name' => 'Hapus Kasbon', 'group' => 'kasbon', 'description' => 'Dapat menghapus kasbon'],
            ['name' => 'kasbon.approve', 'display_name' => 'Setujui Kasbon', 'group' => 'kasbon', 'description' => 'Dapat menyetujui kasbon'],
            ['name' => 'kasbon.reject', 'display_name' => 'Tolak Kasbon', 'group' => 'kasbon', 'description' => 'Dapat menolak kasbon'],

            // Announcement Management (Admin)
            ['name' => 'pengumuman.admin.view', 'display_name' => 'Lihat Pengumuman Admin', 'group' => 'pengumuman', 'description' => 'Dapat melihat daftar pengumuman sebagai admin'],
            ['name' => 'pengumuman.admin.create', 'display_name' => 'Tambah Pengumuman Admin', 'group' => 'pengumuman', 'description' => 'Dapat menambah pengumuman sebagai admin'],
            ['name' => 'pengumuman.admin.edit', 'display_name' => 'Edit Pengumuman Admin', 'group' => 'pengumuman', 'description' => 'Dapat mengedit pengumuman sebagai admin'],
            ['name' => 'pengumuman.admin.delete', 'display_name' => 'Hapus Pengumuman Admin', 'group' => 'pengumuman', 'description' => 'Dapat menghapus pengumuman sebagai admin'],
            ['name' => 'pengumuman.admin.store', 'display_name' => 'Simpan Pengumuman Admin', 'group' => 'pengumuman', 'description' => 'Dapat menyimpan pengumuman sebagai admin'],
            ['name' => 'pengumuman.admin.update', 'display_name' => 'Update Pengumuman Admin', 'group' => 'pengumuman', 'description' => 'Dapat update pengumuman sebagai admin'],
            ['name' => 'pengumuman.admin.destroy', 'display_name' => 'Hapus Pengumuman Admin', 'group' => 'pengumuman', 'description' => 'Dapat menghapus pengumuman sebagai admin'],

            // Employee (Pegawai) Routes
            ['name' => 'pegawai.beranda', 'display_name' => 'Beranda Pegawai', 'group' => 'pegawai', 'description' => 'Dapat mengakses beranda pegawai'],
            ['name' => 'pegawai.absensi.view', 'display_name' => 'Lihat Absensi Pegawai', 'group' => 'pegawai', 'description' => 'Dapat melihat absensi sebagai pegawai'],
            ['name' => 'pegawai.absensi.create', 'display_name' => 'Absen Pegawai', 'group' => 'pegawai', 'description' => 'Dapat melakukan absen sebagai pegawai'],
            ['name' => 'pegawai.riwayat', 'display_name' => 'Riwayat Pegawai', 'group' => 'pegawai', 'description' => 'Dapat melihat riwayat sebagai pegawai'],
            ['name' => 'pegawai.kasbon.view', 'display_name' => 'Lihat Kasbon Pegawai', 'group' => 'pegawai', 'description' => 'Dapat melihat kasbon sebagai pegawai'],
            ['name' => 'pegawai.kasbon.create', 'display_name' => 'Tambah Kasbon Pegawai', 'group' => 'pegawai', 'description' => 'Dapat menambah kasbon sebagai pegawai'],
            ['name' => 'pegawai.kasbon.show', 'display_name' => 'Detail Kasbon Pegawai', 'group' => 'pegawai', 'description' => 'Dapat melihat detail kasbon sebagai pegawai'],
            ['name' => 'pegawai.profil.view', 'display_name' => 'Lihat Profil Pegawai', 'group' => 'pegawai', 'description' => 'Dapat melihat profil sebagai pegawai'],
            ['name' => 'pegawai.profil.update', 'display_name' => 'Update Profil Pegawai', 'group' => 'pegawai', 'description' => 'Dapat mengupdate profil sebagai pegawai'],
            ['name' => 'pegawai.pengumuman.view', 'display_name' => 'Lihat Pengumuman Pegawai', 'group' => 'pegawai', 'description' => 'Dapat melihat pengumuman sebagai pegawai'],
            ['name' => 'pegawai.pengumuman.show', 'display_name' => 'Detail Pengumuman Pegawai', 'group' => 'pegawai', 'description' => 'Dapat melihat detail pengumuman sebagai pegawai'],
            ['name' => 'pegawai.geolocation_help', 'display_name' => 'Bantuan Geolokasi', 'group' => 'pegawai', 'description' => 'Dapat mengakses bantuan geolokasi'],

            // Storage Access
            ['name' => 'storage.access', 'display_name' => 'Akses Storage', 'group' => 'storage', 'description' => 'Dapat mengakses file storage'],
            ['name' => 'storage.link', 'display_name' => 'Storage Link', 'group' => 'storage', 'description' => 'Dapat membuat storage link'],

            // System Management
            ['name' => 'system.artisan', 'display_name' => 'Akses Artisan', 'group' => 'system', 'description' => 'Dapat menjalankan perintah artisan'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full access to all features',
                'is_active' => true
            ]
        );

        $managerRole = Role::firstOrCreate(
            ['name' => 'Manager'],
            [
                'display_name' => 'Manager',
                'description' => 'Manager with limited administrative access',
                'is_active' => true
            ]
        );

        $bendaharaRole = Role::firstOrCreate(
            ['name' => 'Bendahara'],
            [
                'display_name' => 'Bendahara',
                'description' => 'Bendahara with financial access',
                'is_active' => true
            ]
        );

        $employeeRole = Role::firstOrCreate(
            ['name' => 'Karyawan'],
            [
                'display_name' => 'Karyawan',
                'description' => 'Regular employee with basic access',
                'is_active' => true
            ]
        );

        // Assign permissions to roles
        $adminRole->permissions()->sync(Permission::pluck('id'));

        $managerPermissions = Permission::whereIn('name', [
            // Dashboard
            'dashboard.view',

            // User Management
            'user.view',
            'user.create',
            'user.edit',
            'user.update',
            'user.add',
            'user.action',

            // Transaction Management
            'transaksi.view',
            'transaksi.create',
            'transaksi.edit',
            'transaksi.update',
            'transaksi.action',
            'transaksi.export',
            'transaksi.template',
            'transaksi.import',

            // Category Management
            'kategori.view',
            'kategori.create',
            'kategori.edit',
            'kategori.update',
            'kategori.action',
            'kategori.import',
            'kategori.template',

            // Report Management
            'laporan.view',
            'laporan.export',
            'laporan.pdf',
            'laporan.print',

            // Password Management
            'password.view',
            'password.update',

            // Attendance Management
            'absensi.admin.view',
            'absensi.admin.update_status',

            // Loan Management
            'kasbon.view',
            'kasbon.create',
            'kasbon.edit',
            'kasbon.update',
            'kasbon.show',
            'kasbon.store',
            'kasbon.approve',
            'kasbon.reject',

            // Announcement Management
            'pengumuman.admin.view',
            'pengumuman.admin.create',
            'pengumuman.admin.edit',
            'pengumuman.admin.store',
            'pengumuman.admin.update',

            // Employee Routes
            'pegawai.beranda',
            'pegawai.absensi.view',
            'pegawai.absensi.create',
            'pegawai.riwayat',
            'pegawai.kasbon.view',
            'pegawai.kasbon.create',
            'pegawai.kasbon.show',
            'pegawai.profil.view',
            'pegawai.profil.update',
            'pegawai.pengumuman.view',
            'pegawai.pengumuman.show',
            'pegawai.geolocation_help',

            // Storage Access
            'storage.access',
            'storage.link',
        ])->pluck('id');
        $managerRole->permissions()->sync($managerPermissions);

        $bendaharaPermissions = Permission::whereIn('name', [
            // Dashboard
            'dashboard.view',

            // Transaction Management
            'transaksi.view',
            'transaksi.create',
            'transaksi.edit',
            'transaksi.update',
            'transaksi.action',
            'transaksi.export',
            'transaksi.template',
            'transaksi.import',

            // Category Management
            'kategori.view',
            'kategori.create',
            'kategori.edit',
            'kategori.update',
            'kategori.action',
            'kategori.import',
            'kategori.template',

            // Report Management
            'laporan.view',
            'laporan.export',
            'laporan.pdf',
            'laporan.print',

            // Password Management
            'password.view',
            'password.update',

            // Loan Management
            'kasbon.view',
            'kasbon.create',
            'kasbon.edit',
            'kasbon.update',
            'kasbon.show',
            'kasbon.store',
            'kasbon.approve',
            'kasbon.reject',

            // Announcement Management
            'pengumuman.admin.view',

            // Employee Routes
            'pegawai.beranda',
            'pegawai.absensi.view',
            'pegawai.absensi.create',
            'pegawai.riwayat',
            'pegawai.kasbon.view',
            'pegawai.kasbon.create',
            'pegawai.kasbon.show',
            'pegawai.profil.view',
            'pegawai.profil.update',
            'pegawai.pengumuman.view',
            'pegawai.pengumuman.show',
            'pegawai.geolocation_help',

            // Storage Access
            'storage.access',
        ])->pluck('id');
        $bendaharaRole->permissions()->sync($bendaharaPermissions);

        $employeePermissions = Permission::whereIn('name', [
            // Employee Routes
            'pegawai.beranda',
            'pegawai.absensi.view',
            'pegawai.absensi.create',
            'pegawai.riwayat',
            'pegawai.kasbon.view',
            'pegawai.kasbon.create',
            'pegawai.kasbon.show',
            'pegawai.profil.view',
            'pegawai.profil.update',
            'pegawai.pengumuman.view',
            'pegawai.pengumuman.show',
            'pegawai.geolocation_help',

            // Storage Access
            'storage.access',
        ])->pluck('id');
        $employeeRole->permissions()->sync($employeePermissions);

        $this->command->info('Comprehensive permissions and roles created successfully!');
        $this->command->info('Total permissions created: ' . Permission::count());
        $this->command->info('Total roles created: ' . Role::count());
    }
}
