<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBidangRequest;
use App\Http\Requests\UpdateBidangRequest;
use App\Models\Bidang;

class BidangController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Bidang::orderBy('nama_bidang')->get()
        ]);
    }

    public function store(StoreBidangRequest $request)
    {
        $bidang = Bidang::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Bidang berhasil ditambahkan',
            'data' => $bidang
        ], 201);
    }

    public function show(Bidang $bidang)
    {
        return response()->json([
            'success' => true,
            'data' => $bidang
        ]);
    }

    public function update(UpdateBidangRequest $request, Bidang $bidang)
    {
        $bidang->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Bidang berhasil diperbarui',
            'data' => $bidang
        ]);
    }

    public function destroy(Bidang $bidang)
    {
        $bidang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bidang berhasil dihapus'
        ]);
    }
}