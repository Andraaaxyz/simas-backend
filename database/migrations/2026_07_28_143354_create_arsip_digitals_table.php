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
        Schema::create('arsip_digitals', function (Blueprint $table) {
    
            $table->id();
            $table->foreignId('surat_masuk_id')
                ->constrained('surat_masuks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('ukuran_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_digitals');
    }
};
