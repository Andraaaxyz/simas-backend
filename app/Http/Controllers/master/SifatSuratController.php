<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSifatSuratRequest;
use App\Http\Requests\UpdateSifatSuratRequest;
use App\Models\SifatSurat;

class SifatSuratController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => SifatSurat::orderBy('nama_sifat')->get()
        ]);
    }

    public function store(StoreSifatSuratRequest $request)
    {
        $sifatSurat = SifatSurat::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Sifat surat berhasil ditambahkan',
            'data' => $sifatSurat
        ], 201);
    }

    public function show(SifatSurat $sifat_surat)
    {
        return response()->json([
            'success' => true,
            'data' => $sifat_surat
        ]);
    }

    public function update(UpdateSifatSuratRequest $request, SifatSurat $sifat_surat)
    {
        $sifat_surat->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Sifat surat berhasil diperbarui',
            'data' => $sifat_surat
        ]);
    }

    public function destroy(SifatSurat $sifat_surat)
    {
        $sifat_surat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sifat surat berhasil dihapus'
        ]);
    }
}