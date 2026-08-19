<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Http\Requests\StoreDisposisiRequest;
use App\Http\Requests\UpdateDisposisiRequest;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    // Menampilkan semua disposisi
    public function index()
    {
        $disposisis = Disposisi::with([
            'suratMasuk',
            'pengirim',
            'penerima'
        ])
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $disposisis
        ]);
    }

    // Menampilkan detail disposisi
    public function show(Disposisi $disposisi)
    {
        $disposisi->load([
            'suratMasuk',
            'pengirim',
            'penerima'
        ]);

        return response()->json([
            'success' => true,
            'data' => $disposisi
        ]);
    }

    // Membuat disposisi
    public function store(StoreDisposisiRequest $request)
    {
        $data = $request->validated();
    
        $data['dari_user'] = auth()->id();
    
        $disposisi = Disposisi::create($data);
    
        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil dibuat',
            'data' => $disposisi->load([
                'suratMasuk',
                'pengirim',
                'penerima'
            ])
        ]);
    }

    // Mengupdate disposisi
    public function update(
        UpdateDisposisiRequest $request,
        Disposisi $disposisi
    ) {
        // Hanya penerima disposisi yang boleh mengubah
        if ($disposisi->kepada_user !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah disposisi ini'
            ], 403);
        }
    
        $data = $request->validated();
    
        if (
            isset($data['status']) &&
            $data['status'] === 'dibaca' &&
            !$disposisi->dibaca_at
        ) {
            $data['dibaca_at'] = now();
        }
    
        if (
            isset($data['status']) &&
            $data['status'] === 'selesai' &&
            !$disposisi->selesai_at
        ) {
            $data['selesai_at'] = now();
        }
    
        $disposisi->update($data);
    
        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil diperbarui',
            'data' => $disposisi->fresh()->load([
                'suratMasuk',
                'pengirim',
                'penerima'
            ])
        ]);
    }

    // Menghapus disposisi
    public function destroy(Disposisi $disposisi)
    {
        $disposisi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil dihapus'
        ]);
    }
}