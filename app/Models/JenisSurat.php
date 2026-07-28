<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $fillable = [
        'nama_jenis',
    ];

    public function suratMasuks()
{
    return $this->hasMany(SuratMasuk::class);
}
}
