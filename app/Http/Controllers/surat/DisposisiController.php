<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisposisiRequest;
use App\Http\Requests\UpdateDisposisiRequest;
use App\Models\Disposisi;
use App\Models\Notifikasi;
use App\Models\SuratMasuk;
use App\Services\LogAktivitasService;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    protected LogAktivitasService $logService;

    public function __construct(LogAktivitasService $logService)
    {
        $this->logService = $logService;
    }

    // Menampilkan semua disposisi
    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 10), 50);

        $disposisis = Disposisi::with([
            'suratMasuk',
            'pengirim',
            'penerima',
        ])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'data' => $disposisis,
        ]);
    }

    // Menampilkan detail disposisi
    public function show(Disposisi $disposisi)
    {
        $disposisi->load([
            'suratMasuk',
            'pengirim',
            'penerima',
        ]);

        return response()->json([
            'success' => true,
            'data' => $disposisi,
        ]);
    }

    // Membuat disposisi
    public function store(StoreDisposisiRequest $request)
    {
        $data = $request->validated();

        $data['dari_user'] = auth()->id();

        $disposisi = Disposisi::create($data);

        // Sinkronkan status surat menjadi didisposisi
        SuratMasuk::whereKey($disposisi->surat_masuk_id)
            ->update(['status' => 'didisposisi']);

        Notifikasi::create([
            'user_id' => $disposisi->kepada_user,
            'judul' => 'Disposisi Baru',
            'pesan' => 'Anda menerima disposisi baru untuk surat nomor '
                .$disposisi->suratMasuk->no_surat,
            'is_read' => false,
        ]);

        // CATAT AKTIVITAS
        $this->logService->catat(
            'Membuat disposisi untuk surat masuk #'
                .$disposisi->surat_masuk_id,
            $request,
            $disposisi->surat_masuk_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil dibuat',
            'data' => $disposisi->load([
                'suratMasuk',
                'pengirim',
                'penerima',
            ]),
        ], 201);
    }

    // Mengupdate disposisi
    public function update(UpdateDisposisiRequest $request, Disposisi $disposisi)
    {
        $isAdmin = auth()->user()->isAdmin();
        $isPenerima = $disposisi->kepada_user === auth()->id();

        if (! $isAdmin && ! $isPenerima) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah disposisi ini',
            ], 403);
        }

        $data = $request->validated();

        if (isset($data['status'])) {
            if ($data['status'] === 'dibaca' && ! $disposisi->dibaca_at) {
                $data['dibaca_at'] = now();
            }

            if ($data['status'] === 'selesai' && ! $disposisi->selesai_at) {
                $data['selesai_at'] = now();
            }

            if ($data['status'] === 'menunggu') {
                $data['dibaca_at'] = null;
                $data['selesai_at'] = null;
            }
        }

        $disposisi->update($data);

        $this->logService->catat(
            'Mengubah disposisi #'.$disposisi->id,
            $request,
            $disposisi->surat_masuk_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil diperbarui',
            'data' => $disposisi->fresh()->load([
                'suratMasuk',
                'pengirim',
                'penerima',
            ]),
        ]);
    }

    // Menghapus disposisi
    public function destroy(Disposisi $disposisi)
    {
        $idDisposisi = $disposisi->id;
        $idSurat = $disposisi->surat_masuk_id;

        $disposisi->delete();

        $this->logService->catat(
            'Menghapus disposisi #'.$idDisposisi,
            request(),
            $idSurat
        );

        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil dihapus',
        ]);
    }
}
