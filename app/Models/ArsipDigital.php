<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipDigital extends Model
{
    protected $fillable = [
        'surat_masuk_id',
        'nama_file',
        'path_file',
        'ukuran_file',
    ];

    public function suratMasuk()
{
    return $this->belongsTo(SuratMasuk::class);
}
}
