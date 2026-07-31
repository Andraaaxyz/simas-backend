<?php

namespace App\Http\Controllers\Surat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Http\Requests\StoreSuratMasukRequest;
use App\Http\Requests\UpdateSuratMasukRequest;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
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
                  ->orWhere('nama_jenis', 'like', "%{$search}%")
                  ->orWhere('nama_sifat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }
    
        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Urutkan terbaru
        $query->latest();
    
        $surat = $query->paginate(10);
    
        return response()->json([
            'success' => true,
            'message' => 'Data surat masuk berhasil diambil',
            'data' => $surat
        ]);
    }
    public function store(StoreSuratMasukRequest $request)
    {
        $data = $request->validated();
    
        $data['created_by'] = auth()->id();
    
        if ($request->hasFile('file_surat')) {
    
            $data['file_surat'] = $request
                ->file('file_surat')
                ->store('surat-masuk', 'public');
    
        }
    
        $surat = SuratMasuk::create($data);
    
        return response()->json([
            'success' => true,
            'message' => 'Surat masuk berhasil ditambahkan',
            'data' => $surat
        ],201);
    }

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

    public function update(UpdateSuratMasukRequest $request, SuratMasuk $surat_masuk)
{
    $data = $request->validated();

    if ($request->hasFile('file_surat')) {

        if ($surat_masuk->file_surat &&
            Storage::disk('public')->exists($surat_masuk->file_surat)) {

            Storage::disk('public')->delete($surat_masuk->file_surat);
        }

        $data['file_surat'] = $request->file('file_surat')
            ->store('surat-masuk', 'public');
    }

    $surat_masuk->fill($data);
    $surat_masuk->save();

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

    public function destroy(SuratMasuk $surat_masuk)
    {
        if (
            $surat_masuk->file_surat &&
            Storage::disk('public')->exists($surat_masuk->file_surat)
        ) {
            Storage::disk('public')->delete($surat_masuk->file_surat);
        }
    
        $surat_masuk->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Surat masuk berhasil dihapus'
        ]);
    }
}
