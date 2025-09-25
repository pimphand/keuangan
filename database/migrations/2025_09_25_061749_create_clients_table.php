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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('logo')->nullable();
            $table->string('type')->comment('Pemerintahan, Swasta, BUMN, dll')->nullable();
            // save date perdagangan, teknologil, manufaktur, dll
            $table->string('industri')->comment('Perdagangan, Teknologi, Manufaktur, dll')->nullable();
            $table->text('maps')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
