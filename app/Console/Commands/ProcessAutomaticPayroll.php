<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Gajian;
use App\Models\SaldoHistory;
use App\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessAutomaticPayroll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:process {--dry-run : Show what would be processed without actually processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process automatic payroll for users based on their scheduled payroll dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting automatic payroll processing...');

        $today = Carbon::today();
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No actual processing will be done');
        }

        // Get users whose payroll date is today
        $usersToProcess = User::whereNotNull('tanggal_gajian')
            ->whereDay('tanggal_gajian', $today->day)
            ->where('status', 'active')
            ->get();

        if ($usersToProcess->isEmpty()) {
            $this->info('No users scheduled for payroll processing today.');
            return 0;
        }

        $this->info("Found {$usersToProcess->count()} users to process:");

        $processedCount = 0;
        $errorCount = 0;

        foreach ($usersToProcess as $user) {
            try {
                // Check if payroll has already been processed this month
                $currentMonth = $today->format('Y-m');
                $existingPayroll = Gajian::where('user_id', $user->id)
                    ->where('periode_gaji', 'like', $currentMonth . '%')
                    ->first();

                if ($existingPayroll) {
                    $this->warn("Payroll already processed for {$user->name} this month. Skipping...");
                    continue;
                }

                $this->info("Processing payroll for: {$user->name}");

                if (!$isDryRun) {
                    $this->processUserPayroll($user, $today);
                } else {
                    $this->showPayrollPreview($user, $today);
                }

                $processedCount++;
            } catch (\Exception $e) {
                $this->error("Error processing payroll for {$user->name}: " . $e->getMessage());
                Log::error("Automatic payroll error for user {$user->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("\nPayroll processing completed!");
        $this->info("Successfully processed: {$processedCount}");
        if ($errorCount > 0) {
            $this->error("Errors encountered: {$errorCount}");
        }

        return 0;
    }

    /**
     * Process payroll for a single user
     */
    private function processUserPayroll(User $user, Carbon $date)
    {
        DB::beginTransaction();

        try {
            // Calculate payroll components
            $gajiPokok = $user->saldo; // Base salary
            $tunjangan = $user->tunjangan ?? 0; // Allowance
            $potongan = $user->kasbon_terpakai; // Deductions (used kasbon)
            $gajiBersih = $gajiPokok + $tunjangan - $potongan; // Net salary

            // Create saldo history record
            $saldo = SaldoHistory::create([
                'user_id' => $user->id,
                'amount' => $gajiBersih,
                'month_year' => $date->format('Y-m'),
                'notes' => 'Gaji Otomatis - Periode ' . $date->format('F Y'),
                'admin_id' => 1 // System admin ID, you may want to create a system user
            ]);

            // Create transaction record
            Transaksi::create([
                'tanggal' => $date->format('Y-m-d'),
                'jenis' => 'Pengeluaran',
                'kategori_id' => 7, // Assuming 7 is the payroll category
                'nominal' => $gajiBersih,
                'keterangan' => 'Gaji Otomatis ke ' . $user->name . ' sebesar Rp ' . number_format($gajiBersih, 2, ',', '.'),
                'saldo_history_id' => $saldo->id,
            ]);

            // Create payroll record
            Gajian::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'jabatan' => $user->roles()->first()->name ?? 'Karyawan',
                'gaji_pokok' => $gajiPokok,
                'tunjangan' => $tunjangan,
                'potongan' => $potongan,
                'gaji_bersih' => $gajiBersih,
                'periode_gaji' => $date->format('Y-m-01'), // First day of current month
                'tanggal_pembayaran' => $date->format('Y-m-d'),
                'status' => 'Dibayar',
                'keterangan' => 'Gaji otomatis - Sistem'
            ]);

            // Reset used kasbon to 0
            $user->kasbon_terpakai = 0;
            $user->save();

            DB::commit();

            $this->info("✓ Processed: {$user->name} - Net Salary: Rp " . number_format($gajiBersih, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Show payroll preview for dry run
     */
    private function showPayrollPreview(User $user, Carbon $date)
    {
        $gajiPokok = $user->saldo;
        $tunjangan = $user->tunjangan ?? 0;
        $potongan = $user->kasbon_terpakai;
        $gajiBersih = $gajiPokok + $tunjangan - $potongan;

        $this->line("  User: {$user->name}");
        $this->line("  Payroll Date: {$user->tanggal_gajian}");
        $this->line("  Base Salary: Rp " . number_format($gajiPokok, 0, ',', '.'));
        $this->line("  Allowance: Rp " . number_format($tunjangan, 0, ',', '.'));
        $this->line("  Deductions: Rp " . number_format($potongan, 0, ',', '.'));
        $this->line("  Net Salary: Rp " . number_format($gajiBersih, 0, ',', '.'));
        $this->line("  ---");
    }
}
