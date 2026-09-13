<?php

namespace App\Models;

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
        'tujuan_surat',
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

    public function scopeUntukBidang($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('tujuan_surat', $user->bidang?->nama_bidang)
                ->orWhereHas('disposisis', function ($d) use ($user) {
                    $d->whereHas('penerima', function ($p) use ($user) {
                        $p->where('bidang_id', $user->bidang_id);
                    });
                });
        });
    }
}
