<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ArsipDigital;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $terbatas = ! $user->isAdmin() && ! $user->isPimpinan();

        $totalSurat = $terbatas
            ? SuratMasuk::untukBidang($user)->count()
            : SuratMasuk::count();

        $suratBaru = $terbatas
            ? SuratMasuk::untukBidang($user)->where('status', 'baru')->count()
            : SuratMasuk::where('status', 'baru')->count();

        $suratDidisposisi = $terbatas
            ? SuratMasuk::untukBidang($user)->where('status', 'didisposisi')->count()
            : SuratMasuk::where('status', 'didisposisi')->count();

        $suratDiarsipkan = $terbatas
            ? SuratMasuk::untukBidang($user)->where('status', 'diarsipkan')->count()
            : SuratMasuk::where('status', 'diarsipkan')->count();

        $totalDisposisi = $terbatas
            ? Disposisi::where('kepada_user', $user->id)->count()
            : Disposisi::count();

        $totalArsip = $terbatas
            ? ArsipDigital::whereHas('suratMasuk', function ($q) use ($user) {
                $q->untukBidang($user);
            })->count()
            : ArsipDigital::count();

        $totalUser = User::count();

        $suratDasar = $terbatas
            ? SuratMasuk::untukBidang($user)
            : SuratMasuk::query();

        // Surat masuk per bulan
        $suratPerBulan = (clone $suratDasar)
            ->select(
                DB::raw('MONTH(tanggal_terima) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereYear('tanggal_terima', now()->year)
            ->groupBy(DB::raw('MONTH(tanggal_terima)'))
            ->orderBy('bulan')
            ->get();

        // Surat berdasarkan jenis
        $suratBerdasarkanJenis = (clone $suratDasar)
            ->select(
                'jenis_surat_id',
                DB::raw('COUNT(*) as jumlah')
            )
            ->with('jenisSurat:id,nama_jenis')
            ->groupBy('jenis_surat_id')
            ->get();

        $disposisiDasar = $terbatas
            ? Disposisi::where('kepada_user', $user->id)
            : Disposisi::query();

        // Disposisi berdasarkan status
        $disposisiBerdasarkanStatus = (clone $disposisiDasar)
            ->select(
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
            ],
        ]);
    }
}
