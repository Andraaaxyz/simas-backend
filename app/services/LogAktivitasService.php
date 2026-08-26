<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogAktivitasService
{
    public function catat(string $aktivitas, ?Request $request = null): void
    {
        if (!Auth::check()) {
            return;
        }

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'ip_address' => $request?->ip(),
        ]);
    }
}