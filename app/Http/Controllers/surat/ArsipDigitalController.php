<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArsipDigitalRequest;
use App\Models\ArsipDigital;
use App\Models\SuratMasuk;
use App\Services\LogAktivitasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipDigitalController extends Controller
{
    protected LogAktivitasService $logService;

    public function __construct(LogAktivitasService $logService)
    {
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 10), 50);

        $arsips = ArsipDigital::with('suratMasuk')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'data' => $arsips,
        ]);
    }

    public function show(ArsipDigital $arsipDigital)
    {
        return response()->json([
            'success' => true,
            'data' => $arsipDigital->load('suratMasuk'),
        ]);
    }

    public function store(StoreArsipDigitalRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('file');

        $path = $file->store('arsip-digital', 'public');

        $arsip = ArsipDigital::create([
            'surat_masuk_id' => $data['surat_masuk_id'],
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'ukuran_file' => $file->getSize(),
        ]);

        // Sinkronkan status surat menjadi diarsipkan
        SuratMasuk::whereKey($arsip->surat_masuk_id)
            ->update(['status' => 'diarsipkan']);

        $this->logService->catat(
            'Mengupload arsip "'.$arsip->nama_file.'"',
            $request,
            $arsip->surat_masuk_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Arsip berhasil ditambahkan',
            'data' => $arsip->load('suratMasuk'),
        ], 201);
    }

    public function download(ArsipDigital $arsipDigital)
    {
        if (! Storage::disk('public')->exists($arsipDigital->path_file)) {
            return response()->json([
                'success' => false,
                'message' => 'File arsip tidak ditemukan',
            ], 404);
        }

        return Storage::disk('public')
            ->download(
                $arsipDigital->path_file,
                $arsipDigital->nama_file
            );
    }

    public function destroy(ArsipDigital $arsipDigital)
    {
        $suratMasukId = $arsipDigital->surat_masuk_id;
        $namaFile = $arsipDigital->nama_file;

        if (
            $arsipDigital->path_file &&
            Storage::disk('public')->exists($arsipDigital->path_file)
        ) {
            Storage::disk('public')->delete($arsipDigital->path_file);
        }

        $arsipDigital->delete();

        $this->logService->catat(
            'Menghapus arsip "'.$namaFile.'"',
            request(),
            $suratMasukId
        );

        return response()->json([
            'success' => true,
            'message' => 'Arsip berhasil dihapus',
        ]);
    }
}
