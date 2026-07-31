<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJenisSuratRequest;
use App\Http\Requests\UpdateJenisSuratRequest;
use App\Models\JenisSurat;

class JenisSuratController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => JenisSurat::orderBy('nama_jenis')->get()
        ]);
    }

    public function store(StoreJenisSuratRequest $request)
    {
        $jenisSurat = JenisSurat::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jenis surat berhasil ditambahkan',
            'data' => $jenisSurat
        ], 201);
    }

    public function show(JenisSurat $jenis_surat)
    {
        return response()->json([
            'success' => true,
            'data' => $jenis_surat
        ]);
    }

    public function update(UpdateJenisSuratRequest $request, JenisSurat $jenis_surat)
    {
        $jenis_surat->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jenis surat berhasil diperbarui',
            'data' => $jenis_surat
        ]);
    }

    public function destroy(JenisSurat $jenis_surat)
    {
        $jenis_surat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis surat berhasil dihapus'
        ]);
    }
}