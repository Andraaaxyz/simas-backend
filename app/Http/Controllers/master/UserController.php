<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        $users = User::with([
            'role',
            'bidang'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // Menampilkan detail user
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user->load([
                'role',
                'bidang'
            ])
        ]);
    }

    // Menambahkan user
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => $user->load([
                'role',
                'bidang'
            ])
        ], 201);
    }

    // Mengubah user
    public function update(
        UpdateUserRequest $request,
        User $user
    ) {
        $data = $request->validated();

        // Kalau password tidak dikirim, jangan mengubah password lama
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'data' => $user->fresh()->load([
                'role',
                'bidang'
            ])
        ]);
    }

    // Menghapus user
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}