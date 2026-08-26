<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\LogAktivitasService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
   public function login(
    LoginRequest $request,
    LogAktivitasService $logService
)
    {
        $user = User::with(['role', 'bidang'])
            ->where('username', $request->username)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        if ($user->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak aktif'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $logService->catat(
            'Login ke sistem',
            $request
        );

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role->nama_role,
                'bidang' => $user->bidang->nama_bidang,
            ]
        ]);
    }

    public function logout(
        Request $request,
        LogAktivitasService $logService
    ) {
        $logService->catat(
            'Logout dari sistem',
            $request
        );
    
        auth()->user()->currentAccessToken()->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function me()
{
    $user = auth()->user()->load(['role', 'bidang']);

    return response()->json([
        'success' => true,
        'user' => [
            'id' => $user->id,
            'nama' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role->nama_role,
            'bidang' => $user->bidang->nama_bidang,
            'status' => $user->status,
        ]
    ]);
}
}