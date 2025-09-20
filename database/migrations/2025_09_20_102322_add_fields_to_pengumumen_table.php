<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengumumen', function (Blueprint $table) {
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang')->after('status');
            $table->timestamp('tanggal_mulai')->nullable()->after('prioritas');
            $table->timestamp('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->json('target_role')->nullable()->after('tanggal_selesai'); // untuk targeting pengumuman ke role tertentu
            $table->integer('views_count')->default(0)->after('target_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumumen', function (Blueprint $table) {
            $table->dropColumn(['prioritas', 'tanggal_mulai', 'tanggal_selesai', 'target_role', 'views_count']);
        });
    }
};
