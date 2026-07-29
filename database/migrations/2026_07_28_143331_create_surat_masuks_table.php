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
    Schema::create('surat_masuks', function (Blueprint $table) {

        $table->id();

        $table->foreignId('jenis_surat_id')
            ->constrained('jenis_surats')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->foreignId('sifat_surat_id')
            ->constrained('sifat_surats')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->foreignId('created_by')
            ->constrained('users')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->string('no_agenda')->unique();
        $table->string('no_surat')->unique();
        $table->string('asal_surat');
        $table->string('perihal');
        $table->string('lampiran')->nullable();
        $table->date('tanggal_surat');
        $table->date('tanggal_terima');
        $table->string('file_surat');
        $table->string('tujuan_surat')->nullable();
        $table->enum('status', [
            'baru',
            'didisposisi',
            'diarsipkan'
        ])->default('baru');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
