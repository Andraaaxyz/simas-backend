<?php

namespace App\Http\Controllers\Profile;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    // Menampilkan profile user yang sedang login
    public function show(Request $request)
    {
        $user = $request->user()->load([
            'role',
            'bidang'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data profile berhasil diambil',
            'data' => $user
        ]);
    }

    // Mengubah profile user yang sedang login
    public function update(
        UpdateProfileRequest $request
    ) {
        $user = $request->user();

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diperbarui',
            'data' => $user->fresh()->load([
                'role',
                'bidang'
            ])
        ]);
    }
}