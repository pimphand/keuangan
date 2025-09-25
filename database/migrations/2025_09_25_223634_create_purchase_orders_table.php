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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('po_number')->unique();
            $table->enum('client_type', ['Pemerintahan', 'Swasta'])->nullable();
            $table->string('client_name');
            $table->text('client_address');
            $table->string('client_phone_number', 30);
            $table->string('client_nik', 20);
            $table->string('client_ktp_name');
            $table->longText('ktp_photo')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
