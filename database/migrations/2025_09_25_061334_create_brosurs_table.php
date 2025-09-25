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
        Schema::create('brosurs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('gambar');
            $table->string('status')->default('aktif');
            $table->text('deskripsi')->nullable();
            $table->string('file')->nullable();
            $table->foreignId('kategori_brosur_id')->nullable()->constrained();
            $table->bigInteger('harga')->default(0);
            $table->json('spesifikasi')->nullable();
            $table->json('tag')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brosurs');
    }
};
