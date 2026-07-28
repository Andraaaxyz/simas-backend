<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $fillable = [
        'surat_masuk_id',
        'dari_user',
        'kepada_user',
        'instruksi',
        'catatan',
        'status',
        'tanggal_disposisi',
    ];

    public function suratMasuk()
{
    return $this->belongsTo(SuratMasuk::class);
}

public function pengirim()
{
    return $this->belongsTo(User::class, 'dari_user');
}

public function penerima()
{
    return $this->belongsTo(User::class, 'kepada_user');
}
}
