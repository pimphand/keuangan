# Setup Cronjob untuk Penggajian Otomatis

## Deskripsi
Sistem penggajian otomatis ini akan memproses gaji karyawan berdasarkan tanggal yang telah ditetapkan di field `tanggal_gajian` pada tabel `users`.

## Cara Kerja
1. Sistem akan mengecek setiap hari apakah ada karyawan yang jadwal gajinya jatuh pada hari tersebut
2. Jika ada, sistem akan memproses gaji secara otomatis dengan perhitungan:
   - **Gaji Pokok**: Menggunakan nilai dari field `saldo` user
   - **Tunjangan**: Menggunakan nilai dari field `tunjangan` user
   - **Potongan**: Menggunakan nilai dari field `kasbon_terpakai` user
   - **Gaji Bersih**: Gaji Pokok + Tunjangan - Potongan

## Setup Cronjob

### 1. Edit Crontab
```bash
crontab -e
```

### 2. Tambahkan Entry Cronjob
Jalankan setiap hari pada jam 08:00 pagi:
```bash
0 8 * * * cd /home/co-026/Project/keuangan && php artisan payroll:process >> /var/log/payroll.log 2>&1
```

Atau untuk testing, jalankan setiap 5 menit:
```bash
*/5 * * * * cd /home/co-026/Project/keuangan && php artisan payroll:process >> /var/log/payroll.log 2>&1
```

### 3. Alternatif: Menggunakan Laravel Scheduler
Tambahkan di `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('payroll:process')
             ->dailyAt('08:00')
             ->withoutOverlapping()
             ->runInBackground();
}
```

Kemudian jalankan scheduler di crontab:
```bash
* * * * * cd /home/co-026/Project/keuangan && php artisan schedule:run >> /dev/null 2>&1
```

## Testing Command

### Dry Run (Preview tanpa eksekusi)
```bash
php artisan payroll:process --dry-run
```

### Manual Execution
```bash
php artisan payroll:process
```

## Log Monitoring
- Log aplikasi: `storage/logs/laravel.log`
- Log cronjob: `/var/log/payroll.log` (jika menggunakan crontab)
- Log sistem: `/var/log/cron.log`

## Contoh Data Input

### User dengan tanggal_gajian = 25 (setiap tanggal 25)
```sql
UPDATE users SET tanggal_gajian = '2024-01-25' WHERE id = 1;
```

### User dengan tanggal_gajian = 1 (setiap tanggal 1)
```sql
UPDATE users SET tanggal_gajian = '2024-01-01' WHERE id = 2;
```

## Troubleshooting

### 1. Cek Status Cronjob
```bash
sudo systemctl status cron
```

### 2. Cek Log Cronjob
```bash
tail -f /var/log/payroll.log
```

### 3. Test Command Manual
```bash
cd /home/co-026/Project/keuangan
php artisan payroll:process --dry-run
```

### 4. Cek Permission File
```bash
chmod +x /home/co-026/Project/keuangan/artisan
```

## Keamanan
- Pastikan file log tidak dapat diakses dari web
- Gunakan user yang memiliki permission terbatas untuk menjalankan cronjob
- Monitor log secara berkala untuk mendeteksi error

## Backup
- Backup database sebelum menjalankan cronjob pertama kali
- Simpan backup konfigurasi cronjob
- Dokumentasikan semua perubahan yang dilakukan
