<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengumuman;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengumuman = [
            [
                'judul' => 'Rapat Mingguan Proyek Alpha',
                'isi' => "Agenda: Evaluasi kemajuan, perencanaan sprint, dan pembagian tugas.\n\nJadwal: Selasa, 26 Oktober 2025, pukul 10:00 WITA\nTempat: Ruang Rapat Lt. 2\n\nMohon hadir tepat waktu dan siapkan laporan kemajuan proyek masing-masing.",
                'status' => 'aktif',
                'prioritas' => 'tinggi',
                'tanggal_mulai' => Carbon::now()->subDays(1),
                'tanggal_selesai' => Carbon::now()->addDays(7),
                'target_role' => ['pegawai'],
                'created_by' => 1,
            ],
            [
                'judul' => 'Agenda Perusahaan: Pelatihan Keterampilan',
                'isi' => "Agenda: Pelatihan coding lanjutan untuk tim developer.\n\nJadwal: Kamis, 28 Oktober 2025, pukul 09:00 WITA - 16:00 WITA\nTempat: Ruang Pelatihan Lt. 1\n\nPelatihan ini akan membahas framework terbaru dan best practices dalam pengembangan aplikasi. Silakan daftar melalui HRD.",
                'status' => 'aktif',
                'prioritas' => 'sedang',
                'tanggal_mulai' => Carbon::now()->subDays(2),
                'tanggal_selesai' => Carbon::now()->addDays(14),
                'target_role' => ['pegawai'],
                'created_by' => 1,
            ],
            [
                'judul' => 'Pembaruan Sistem Kehadiran',
                'isi' => "Sistem kehadiran telah diperbarui dengan fitur-fitur baru:\n\n- Geolocation tracking yang lebih akurat\n- Notifikasi real-time\n- Laporan kehadiran yang lebih detail\n\nSilakan update aplikasi mobile Anda untuk mendapatkan fitur terbaru. Jika mengalami kendala, hubungi tim IT.",
                'status' => 'aktif',
                'prioritas' => 'rendah',
                'tanggal_mulai' => Carbon::now()->subDays(3),
                'tanggal_selesai' => Carbon::now()->addDays(30),
                'target_role' => ['pegawai'],
                'created_by' => 1,
            ],
            [
                'judul' => 'Libur Nasional: Hari Sumpah Pemuda',
                'isi' => "Mengingatkan bahwa pada tanggal 28 Oktober 2025 adalah Hari Sumpah Pemuda yang merupakan hari libur nasional.\n\nKantor akan tutup pada hari tersebut. Selamat berlibur dan jangan lupa untuk melanjutkan aktivitas kehadiran normal pada hari kerja berikutnya.",
                'status' => 'aktif',
                'prioritas' => 'sedang',
                'tanggal_mulai' => Carbon::now()->subDays(5),
                'tanggal_selesai' => Carbon::now()->addDays(3),
                'target_role' => ['pegawai'],
                'created_by' => 1,
            ],
            [
                'judul' => 'Kebijakan Work From Home',
                'isi' => "Mulai bulan depan, perusahaan akan menerapkan kebijakan Work From Home (WFH) fleksibel:\n\n- Maksimal 2 hari WFH per minggu\n- Harus mendapat persetujuan atasan langsung\n- Tetap wajib melakukan absensi online\n- Meeting tetap diadakan secara hybrid\n\nDetail kebijakan akan disampaikan dalam rapat departemen minggu depan.",
                'status' => 'aktif',
                'prioritas' => 'sedang',
                'tanggal_mulai' => Carbon::now()->subDays(7),
                'tanggal_selesai' => Carbon::now()->addDays(45),
                'target_role' => ['pegawai'],
                'created_by' => 1,
            ]
        ];

        foreach ($pengumuman as $data) {
            Pengumuman::create($data);
        }
    }
}
