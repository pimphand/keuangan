<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kasbon;
use App\Models\User;

class KasbonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user dengan level pengawas (karena pegawai tidak ada di enum)
        $pegawai = User::firstOrCreate(
            ['email' => 'pegawai@example.com'],
            [
                'name' => 'Pegawai Test',
                'password' => bcrypt('password'),
                'level' => 'pengawas',
                'kasbon' => 500000,
                'saldo' => 0,
                'status' => 'active',
                'foto' => 'default.jpg'
            ]
        );

        // Buat beberapa data kasbon untuk testing
        $kasbons = [
            [
                'user_id' => $pegawai->id,
                'nominal' => 100000,
                'keterangan' => 'Kebutuhan mendesak keluarga',
                'status' => 'pending',
                'disetujui_id' => null,
            ],
            [
                'user_id' => $pegawai->id,
                'nominal' => 250000,
                'keterangan' => 'Biaya kesehatan',
                'status' => 'disetujui',
                'disetujui_id' => 1,
            ],
            [
                'user_id' => $pegawai->id,
                'nominal' => 150000,
                'keterangan' => 'Perbaikan kendaraan',
                'status' => 'ditolak',
                'disetujui_id' => 1,
                'alasan' => 'Dokumen tidak lengkap'
            ],
        ];

        foreach ($kasbons as $kasbonData) {
            Kasbon::create($kasbonData);
        }
    }
}
