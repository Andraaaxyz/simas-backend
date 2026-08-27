<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAktivitasService
{
    public function catat(
        string $aktivitas,
        ?Request $request = null,
        ?int $suratMasukId = null
    ): void {
        if (!Auth::check()) {
            return;
        }

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'surat_masuk_id' => $suratMasukId,
            'aktivitas' => $aktivitas,
            'ip_address' => $request?->ip(),
        ]);
    }
}