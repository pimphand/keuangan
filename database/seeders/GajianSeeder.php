<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gajian;
use App\Models\User;
use Carbon\Carbon;

class GajianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with role 'Karyawan'
        $pegawais = User::where('role', 'Karyawan')->get();

        foreach ($pegawais as $pegawai) {
            // Create salary slips for the last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $periode = Carbon::now()->subMonths($i);
                $gajiPokok = rand(3000000, 8000000); // Random basic salary between 3-8 million
                $tunjangan = rand(500000, 2000000); // Random allowance between 500k-2 million
                $potongan = rand(100000, 1000000); // Random deduction between 100k-1 million
                $gajiBersih = $gajiPokok + $tunjangan - $potongan;

                Gajian::create([
                    'user_id' => $pegawai->id,
                    'nama' => $pegawai->name,
                    'jabatan' => $pegawai->jabatan ?? 'Karyawan',
                    'gaji_pokok' => $gajiPokok,
                    'tunjangan' => $tunjangan,
                    'potongan' => $potongan,
                    'gaji_bersih' => $gajiBersih,
                    'periode_gaji' => $periode->format('Y-m-01'),
                    'tanggal_pembayaran' => $periode->addDays(rand(1, 5)),
                    'status' => $this->getRandomStatus(),
                    'keterangan' => $this->getRandomKeterangan()
                ]);
            }
        }
    }

    private function getRandomStatus()
    {
        $statuses = ['pending', 'approved', 'paid'];
        return $statuses[array_rand($statuses)];
    }

    private function getRandomKeterangan()
    {
        $keterangans = [
            null,
            'Potongan untuk kasbon bulan lalu',
            'Bonus kinerja bulan ini',
            'Potongan untuk absensi terlambat',
            'Tunjangan transportasi',
            'Potongan untuk pinjaman karyawan'
        ];
        return $keterangans[array_rand($keterangans)];
    }
}
