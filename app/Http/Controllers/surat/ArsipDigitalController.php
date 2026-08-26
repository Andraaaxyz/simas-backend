<?php

namespace App\Http\Controllers\Surat;

use App\Services\LogAktivitasService;
use App\Http\Requests\StoreArsipDigitalRequest;
use App\Models\ArsipDigital;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArsipDigitalController extends Controller
{
    public function index()
    {
        $arsips = ArsipDigital::with('suratMasuk')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $arsips
        ]);
    }

    public function show(ArsipDigital $arsipDigital)
    {
        return response()->json([
            'success' => true,
            'data' => $arsipDigital->load('suratMasuk')
        ]);
    }

    public function store(StoreArsipDigitalRequest $request, LogAktivitasService $logService) {
        $data = $request->validated();
    
        $file = $request->file('file');
    
        $path = $file->store('arsip-digital', 'public');
    
        $arsip = ArsipDigital::create([
            'surat_masuk_id' => $data['surat_masuk_id'],
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'ukuran_file' => $file->getSize(),
        ]);
    
        $logService->catat(
            'Mengupload arsip untuk surat  #' . $arsip->surat_masuk_id,
            $request
        );
    
        return response()->json([
            'success' => true,
            'message' => 'Arsip berhasil ditambahkan',
            'data' => $arsip->load('suratMasuk')
        ], 201);
    }

    public function download(ArsipDigital $arsipDigital)
    {
        if (!Storage::disk('public')->exists($arsipDigital->path_file)) {
            return response()->json([
                'success' => false,
                'message' => 'File arsip tidak ditemukan'
            ], 404);
        }

        return Storage::disk('public')
            ->download(
                $arsipDigital->path_file,
                $arsipDigital->nama_file
            );
    }

    public function destroy(ArsipDigital $arsipDigital, LogAktivitasService $logService) {
        $suratMasukId = $arsipDigital->surat_masuk_id;
        $namaFile = $arsipDigital->nama_file;
    
        if (
            $arsipDigital->path_file &&
            Storage::disk('public')->exists($arsipDigital->path_file)
        ) {
            Storage::disk('public')->delete($arsipDigital->path_file);
        }
    
        $arsipDigital->delete();
    
        $logService->catat(
            'Menghapus arsip "' . $namaFile .
            '" dari surat masuk #' . $suratMasukId,
            request()
        );
    
        return response()->json([
            'success' => true,
            'message' => 'Arsip berhasil dihapus'
        ]);
    }
}