<?php

namespace App\Http\Controllers\Surat;

use App\Services\LogAktivitasService;
use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\Notifikasi;
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

        Notifikasi::create([
            'user_id' => $disposisi->kepada_user,
            'judul' => 'Disposisi Baru',
            'pesan' => 'Anda menerima disposisi baru untuk surat nomor '
                . $disposisi->suratMasuk->no_surat,
            'is_read' => false,
        ]);

        $logService->catat(
            'Membuat disposisi untuk surat #' . $disposisi->surat_masuk_id,
            $request
        );
    
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
    public function update(UpdateDisposisiRequest $request,Disposisi $disposisi,LogAktivitasService $logService
    ) {
        if (
            $disposisi->kepada_user !== auth()->id() &&
            auth()->user()->role?->nama_role !== 'Admin'
        ) {
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
    
        $logService->catat(
            'Mengubah status disposisi #' . $disposisi->id .
            ' menjadi ' . $disposisi->status,
            $request
        );
    
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