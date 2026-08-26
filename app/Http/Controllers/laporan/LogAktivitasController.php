<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->latest('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}