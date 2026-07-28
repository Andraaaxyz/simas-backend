<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $fillable = [
        'jenis_surat_id',
        'sifat_surat_id',
        'created_by',
        'no_agenda',
        'no_surat',
        'asal_surat',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
        'file_surat',
        'status',
        'lampiran',
    ];

    public function jenisSurat()
{
    return $this->belongsTo(JenisSurat::class);
}

public function sifatSurat()
{
    return $this->belongsTo(SifatSurat::class);
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function disposisis()
{
    return $this->hasMany(Disposisi::class);
}

public function arsips()
{
    return $this->hasMany(ArsipDigital::class);
}
}
