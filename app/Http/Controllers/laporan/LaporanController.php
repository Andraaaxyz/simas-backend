<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function suratMasuk(Request $request)
    {
        $query = SuratMasuk::with([
            'jenisSurat',
            'sifatSurat',
            'creator'
        ]);

        // Filter tanggal awal
        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'tanggal_terima',
                '>=',
                $request->tanggal_awal
            );
        }

        // Filter tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal_terima',
                '<=',
                $request->tanggal_akhir
            );
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis surat
        if ($request->filled('jenis_surat_id')) {
            $query->where(
                'jenis_surat_id',
                $request->jenis_surat_id
            );
        }

        // Filter sifat surat
        if ($request->filled('sifat_surat_id')) {
            $query->where(
                'sifat_surat_id',
                $request->sifat_surat_id
            );
        }

        $laporan = $query
            ->latest('tanggal_terima')
            ->paginate(20)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Laporan surat masuk berhasil diambil',
            'data' => $laporan
        ]);
    }
}