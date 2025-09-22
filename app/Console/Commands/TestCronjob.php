<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TestCronjob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronjob:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command to verify cronjob is working properly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');

        $this->info("✅ Cronjob Test - {$timestamp}");
        $this->info("Cronjob is working properly!");

        // Log to file for verification
        Log::info("Cronjob test executed at: {$timestamp}");

        // Create a simple test file to verify execution
        $testFile = storage_path('logs/cronjob_test.txt');
        file_put_contents($testFile, "Cronjob test executed at: {$timestamp}\n", FILE_APPEND);

        $this->info("Test log written to: {$testFile}");

        return 0;
    }
}
