<?php

namespace App\Http\Controllers\Notifikasi;

use App\Models\Notifikasi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    public function unread()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    public function read(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke notifikasi ini'
            ], 403);
        }

        $notifikasi->update([
            'is_read' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca',
            'data' => $notifikasi
        ]);
    }
}