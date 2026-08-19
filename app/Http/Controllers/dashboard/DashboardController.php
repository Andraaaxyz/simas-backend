<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\SuratMasuk;
use App\Models\Disposisi;
use App\Models\ArsipDigital;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSurat = SuratMasuk::count();

        $suratBaru = SuratMasuk::where('status', 'baru')->count();

        $suratDidisposisi = SuratMasuk::where('status', 'didisposisi')->count();

        $suratDiarsipkan = SuratMasuk::where('status', 'diarsipkan')->count();

        $totalDisposisi = Disposisi::count();

        $totalArsip = ArsipDigital::count();

        $totalUser = User::count();

        // Surat masuk per bulan
        $suratPerBulan = SuratMasuk::select(
                DB::raw('MONTH(tanggal_terima) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereYear('tanggal_terima', now()->year)
            ->groupBy(DB::raw('MONTH(tanggal_terima)'))
            ->orderBy('bulan')
            ->get();

        // Surat berdasarkan jenis
        $suratBerdasarkanJenis = SuratMasuk::select(
                'jenis_surat_id',
                DB::raw('COUNT(*) as jumlah')
            )
            ->with('jenisSurat:id,nama_jenis')
            ->groupBy('jenis_surat_id')
            ->get();

        // Disposisi berdasarkan status
        $disposisiBerdasarkanStatus = Disposisi::select(
                'status',
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('status')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil',
            'data' => [
                'summary' => [
                    'total_surat' => $totalSurat,
                    'surat_baru' => $suratBaru,
                    'surat_didisposisi' => $suratDidisposisi,
                    'surat_diarsipkan' => $suratDiarsipkan,
                    'total_disposisi' => $totalDisposisi,
                    'total_arsip' => $totalArsip,
                    'total_user' => $totalUser,
                ],

                'surat_per_bulan' => $suratPerBulan,

                'surat_berdasarkan_jenis' => $suratBerdasarkanJenis,

                'disposisi_berdasarkan_status' => $disposisiBerdasarkanStatus,
            ]
        ]);
    }
}