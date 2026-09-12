<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\LogAktivitasService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected LogAktivitasService $logService;

    public function __construct(LogAktivitasService $logService)
    {
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 10), 50);

        $users = User::with([
            'role',
            'bidang',
        ])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user->load([
                'role',
                'bidang',
            ]),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        $this->logService->catat(
            'Menambahkan user "'.$user->nama.'"',
            $request
        );

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => $user->load([
                'role',
                'bidang',
            ]),
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        $this->logService->catat(
            'Mengubah user "'.$user->nama.'"',
            $request
        );

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'data' => $user->fresh()->load([
                'role',
                'bidang',
            ]),
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun sendiri',
            ], 400);
        }

        $memilikiDataTerkait = SuratMasuk::where('created_by', $user->id)->exists()
            || Disposisi::where('dari_user', $user->id)->exists()
            || Disposisi::where('kepada_user', $user->id)->exists();

        if ($memilikiDataTerkait) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak dapat dihapus karena memiliki data surat/disposisi terkait. Nonaktifkan statusnya saja.',
            ], 409);
        }

        $nama = $user->nama;

        $user->delete();

        $this->logService->catat(
            'Menghapus user "'.$nama.'"',
            request()
        );

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }
}
