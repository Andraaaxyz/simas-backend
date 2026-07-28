<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $fillable = [
        'user_id',
        'aktivitas',
        'ip_address',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
