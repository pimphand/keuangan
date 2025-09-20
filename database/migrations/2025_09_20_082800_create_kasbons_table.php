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
        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('nominal', 10, 2);
            $table->string('keterangan');
            $table->string('status')->default('pending')->comment('pending, disetujui, ditolak');
            $table->foreignId('disetujui_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('alasan')->nullable();
            $table->timestamps();
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->bigInteger('kasbon_id')->nullable()->comment('mengarah ke id kasbon');
        });

        Schema::table('users', callback: function (Blueprint $table) {
            $table->bigInteger('kasbon')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbons');

        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('kasbon_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kasbon');
        });
    }
};
