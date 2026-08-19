<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Master\BidangController;
use App\Http\Controllers\Master\SifatSuratController;
use App\Http\Controllers\Master\JenisSuratController;
use App\Http\Controllers\Surat\SuratMasukController;
use App\Http\Controllers\Surat\DisposisiController;
use App\Http\Controllers\Master\UserController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //login dan logout 
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    //master aplikasi
    Route::apiResource('bidangs', BidangController::class);
    Route::apiResource('jenis-surat', JenisSuratController::class);
    Route::apiResource('sifat-surat', SifatSuratController::class);
    Route::apiResource('users', UserController::class);
    

    //persuratan
    Route::apiResource('surat-masuk', SuratMasukController::class);
    Route::apiResource('disposisi', DisposisiController::class);
    Route::put('/disposisi/{disposisi}', [DisposisiController::class, 'update']);
});
