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
        Schema::create('disposisis', function (Blueprint $table) {
    
            $table->id();
    
            $table->foreignId('surat_masuk_id')
                ->constrained('surat_masuks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
    
            $table->foreignId('dari_user')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
    
            $table->foreignId('kepada_user')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
    
            $table->date('tanggal_disposisi');
            $table->text('instruksi');
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'menunggu',
                'dibaca',
                'diproses',
                'selesai'
            ])->default('menunggu');
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};
