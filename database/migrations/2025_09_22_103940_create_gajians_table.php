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
        Schema::create('gajians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('jabatan');
            $table->bigInteger('gaji_pokok');
            $table->bigInteger('tunjangan');
            $table->bigInteger('potongan');
            $table->bigInteger('gaji_bersih');
            $table->date('periode_gaji');
            $table->date('tanggal_pembayaran')->nullable();
            $table->string('status')->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->date('tanggal_gajian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajians');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tanggal_gajian');
        });
    }
};
