<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifatSurat extends Model
{
    protected $fillable = [
        'nama_sifat',
    ];

    public function suratMasuks()
{
    return $this->hasMany(SuratMasuk::class);
}
}
