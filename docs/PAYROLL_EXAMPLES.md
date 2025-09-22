# Contoh Data dan Testing Penggajian Otomatis

## Contoh Data User untuk Testing

### 1. User dengan Gaji Pokok Rp 5.000.000
```sql
-- Update user dengan gaji pokok 5 juta, tunjangan 1 juta, kasbon terpakai 500 ribu
UPDATE users SET 
    saldo = 5000000,
    tunjangan = 1000000,
    kasbon_terpakai = 500000,
    tanggal_gajian = '2024-01-25',
    status = 'aktif'
WHERE id = 1;

-- Hasil perhitungan:
-- Gaji Pokok: Rp 5.000.000
-- Tunjangan: Rp 1.000.000
-- Potongan: Rp 500.000
-- Gaji Bersih: Rp 5.500.000
```

### 2. User dengan Gaji Pokok Rp 3.500.000
```sql
-- Update user dengan gaji pokok 3.5 juta, tunjangan 500 ribu, kasbon terpakai 200 ribu
UPDATE users SET 
    saldo = 3500000,
    tunjangan = 500000,
    kasbon_terpakai = 200000,
    tanggal_gajian = '2024-01-01',
    status = 'aktif'
WHERE id = 2;

-- Hasil perhitungan:
-- Gaji Pokok: Rp 3.500.000
-- Tunjangan: Rp 500.000
-- Potongan: Rp 200.000
-- Gaji Bersih: Rp 3.800.000
```

### 3. User dengan Gaji Pokok Rp 7.000.000
```sql
-- Update user dengan gaji pokok 7 juta, tunjangan 2 juta, kasbon terpakai 1 juta
UPDATE users SET 
    saldo = 7000000,
    tunjangan = 2000000,
    kasbon_terpakai = 1000000,
    tanggal_gajian = '2024-01-15',
    status = 'aktif'
WHERE id = 3;

-- Hasil perhitungan:
-- Gaji Pokok: Rp 7.000.000
-- Tunjangan: Rp 2.000.000
-- Potongan: Rp 1.000.000
-- Gaji Bersih: Rp 8.000.000
```

## Testing Scenarios

### Scenario 1: Testing dengan Dry Run
```bash
# Jalankan preview tanpa eksekusi
php artisan payroll:process --dry-run

# Output yang diharapkan:
# Starting automatic payroll processing...
# DRY RUN MODE - No actual processing will be done
# Found X users to process:
#   User: John Doe
#   Payroll Date: 2024-01-25
#   Base Salary: Rp 5.000.000
#   Allowance: Rp 1.000.000
#   Deductions: Rp 500.000
#   Net Salary: Rp 5.500.000
#   ---
```

### Scenario 2: Testing Manual Execution
```bash
# Jalankan penggajian manual
php artisan payroll:process

# Output yang diharapkan:
# Starting automatic payroll processing...
# Found 1 users to process:
# Processing payroll for: John Doe
# ✓ Processed: John Doe - Net Salary: Rp 5.500.000
# 
# Payroll processing completed!
# Successfully processed: 1
```

### Scenario 3: Testing dengan Multiple Users
```sql
-- Setup multiple users dengan tanggal gaji yang sama
UPDATE users SET tanggal_gajian = '2024-01-25' WHERE id IN (1, 2, 3);
```

## Monitoring dan Logging

### 1. Cek Log Aplikasi
```bash
tail -f storage/logs/laravel.log
```

### 2. Cek Log Cronjob
```bash
tail -f /var/log/payroll.log
```

### 3. Cek Database Records
```sql
-- Cek record gajian yang baru dibuat
SELECT * FROM gajians WHERE created_at >= CURDATE();

-- Cek saldo history
SELECT * FROM saldo_histories WHERE created_at >= CURDATE();

-- Cek transaksi
SELECT * FROM transaksis WHERE created_at >= CURDATE();
```

## Error Handling

### 1. User Sudah Digaji Bulan Ini
```
Payroll already processed for John Doe this month. Skipping...
```

### 2. User Tidak Aktif
```sql
-- User dengan status tidak aktif tidak akan diproses
UPDATE users SET status = 'tidak_aktif' WHERE id = 1;
```

### 3. User Tanpa Tanggal Gaji
```sql
-- User tanpa tanggal_gajian tidak akan diproses
UPDATE users SET tanggal_gajian = NULL WHERE id = 1;
```

## Rollback Testing

### 1. Hapus Record Gaji untuk Testing Ulang
```sql
-- Hapus record gaji bulan ini
DELETE FROM gajians WHERE periode_gaji LIKE '2024-01%';

-- Hapus saldo history bulan ini
DELETE FROM saldo_histories WHERE month_year = '2024-01';

-- Hapus transaksi bulan ini
DELETE FROM transaksis WHERE tanggal LIKE '2024-01%';

-- Reset kasbon terpakai
UPDATE users SET kasbon_terpakai = 500000 WHERE id = 1;
```

## Performance Testing

### 1. Test dengan Banyak User
```sql
-- Buat 100 user untuk testing
INSERT INTO users (name, email, password, saldo, tunjangan, kasbon_terpakai, tanggal_gajian, status, created_at, updated_at)
SELECT 
    CONCAT('User ', n),
    CONCAT('user', n, '@example.com'),
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    5000000,
    1000000,
    500000,
    '2024-01-25',
    'aktif',
    NOW(),
    NOW()
FROM (
    SELECT @row := @row + 1 AS n FROM 
    (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) t1,
    (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) t2,
    (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) t3,
    (SELECT @row := 0) r
) numbers
WHERE n <= 100;
```

## Best Practices

1. **Selalu test dengan dry-run terlebih dahulu**
2. **Backup database sebelum menjalankan cronjob pertama**
3. **Monitor log secara berkala**
4. **Set tanggal gaji yang realistis (biasanya akhir bulan)**
5. **Pastikan user memiliki data yang lengkap sebelum diproses**
