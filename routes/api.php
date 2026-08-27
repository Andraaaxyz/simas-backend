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
use App\Http\Controllers\Surat\ArsipDigitalController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Notifikasi\NotifikasiController;
use App\Http\Controllers\Laporan\LogAktivitasController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //login dan logout 
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //timeline
    Route::get('/surat-masuk/{surat_masuk}/timeline', [SuratMasukController::class, 'timeline']);
    
    //master aplikasi
    Route::apiResource('bidangs', BidangController::class);
    Route::apiResource('jenis-surat', JenisSuratController::class);
    Route::apiResource('sifat-surat', SifatSuratController::class);
    Route::apiResource('users', UserController::class);
    

    //persuratan
    Route::apiResource('surat-masuk', SuratMasukController::class);
    Route::apiResource('disposisi', DisposisiController::class);
    Route::put('/disposisi/{disposisi}', [DisposisiController::class, 'update']);

    //arsip
    Route::get('/arsip-digital', [ArsipDigitalController::class, 'index']);
    Route::get('/arsip-digital/{arsipDigital}', [ArsipDigitalController::class, 'show']);
    Route::post('/arsip-digital', [ArsipDigitalController::class, 'store']);
    Route::get('/arsip-digital/{arsipDigital}/download', [ArsipDigitalController::class, 'download']);
    Route::delete('/arsip-digital/{arsipDigital}', [ArsipDigitalController::class, 'destroy']); 

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::get('/notifikasi/unread', [NotifikasiController::class, 'unread']);
    Route::put('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'read']);

    //log aktifitas
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index']);

});
