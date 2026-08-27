<?php

namespace App\Http\Controllers\Surat;

use Illuminate\Http\Request;
use App\Services\LogAktivitasService;
use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\LogAktivitas;
use App\Http\Requests\StoreSuratMasukRequest;
use App\Http\Requests\UpdateSuratMasukRequest;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    // =========================
    // MENAMPILKAN SEMUA SURAT
    // =========================
    public function index(Request $request)
    {
        $query = SuratMasuk::with([
            'jenisSurat',
            'sifatSurat',
            'creator'
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('no_agenda', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->latest();

        $surat = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Data surat masuk berhasil diambil',
            'data' => $surat
        ]);
    }


    // =========================
    // MENAMBAHKAN SURAT
    // =========================
    public function store(
        StoreSuratMasukRequest $request,
        LogAktivitasService $logService
    ) {
        $data = $request->validated();

        $data['created_by'] = auth()->id();

        if ($request->hasFile('file_surat')) {
            $data['file_surat'] = $request
                ->file('file_surat')
                ->store('surat-masuk', 'public');
        }

        $surat = SuratMasuk::create($data);

        // Catat aktivitas
        $logService->catat(
            'Menambahkan surat masuk nomor "' . $surat->no_surat . '"',
            $request,
            $surat->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Surat masuk berhasil ditambahkan',
            'data' => $surat->load([
                'jenisSurat',
                'sifatSurat',
                'creator'
            ])
        ], 201);
    }


    // =========================
    // DETAIL SURAT
    // =========================
    public function show(SuratMasuk $surat_masuk)
    {
        return response()->json([
            'success' => true,
            'data' => $surat_masuk->load([
                'jenisSurat',
                'sifatSurat',
                'creator'
            ])
        ]);
    }


    // =========================
    // UPDATE SURAT
    // =========================
    public function update(
        UpdateSuratMasukRequest $request,
        SuratMasuk $surat_masuk,
        LogAktivitasService $logService
    ) {
        $data = $request->validated();

        if ($request->hasFile('file_surat')) {

            if (
                $surat_masuk->file_surat &&
                Storage::disk('public')->exists($surat_masuk->file_surat)
            ) {
                Storage::disk('public')->delete($surat_masuk->file_surat);
            }

            $data['file_surat'] = $request
                ->file('file_surat')
                ->store('surat-masuk', 'public');
        }

        $surat_masuk->update($data);

        // Catat aktivitas
        $logService->catat(
            'Mengubah surat masuk nomor "' . $surat_masuk->no_surat . '"',
            $request,
            $surat_masuk->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Surat masuk berhasil diperbarui',
            'data' => $surat_masuk->fresh()->load([
                'jenisSurat',
                'sifatSurat',
                'creator'
            ])
        ]);
    }


    // =========================
    // HAPUS SURAT
    // =========================
    public function destroy(
        SuratMasuk $surat_masuk,
        LogAktivitasService $logService
    ) {
        $idSurat = $surat_masuk->id;
        $noSurat = $surat_masuk->no_surat;

        // Hapus file surat
        if (
            $surat_masuk->file_surat &&
            Storage::disk('public')->exists($surat_masuk->file_surat)
        ) {
            Storage::disk('public')->delete($surat_masuk->file_surat);
        }

        // Catat log SEBELUM surat dihapus
        $logService->catat(
            'Menghapus surat masuk nomor "' . $noSurat . '"',
            request(),
            $idSurat
        );

        $surat_masuk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surat masuk berhasil dihapus'
        ]);
    }


    // =========================
    // TIMELINE SURAT
    // =========================
    public function timeline(SuratMasuk $surat_masuk)
    {
        $timeline = LogAktivitas::with('user')
            ->where('surat_masuk_id', $surat_masuk->id)
            ->latest('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $timeline
        ]);
    }
}