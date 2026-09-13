<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\Laporan\LogAktivitasController;
use App\Http\Controllers\Master\BidangController;
use App\Http\Controllers\Master\JenisSuratController;
use App\Http\Controllers\Master\SifatSuratController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Notifikasi\NotifikasiController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Surat\ArsipDigitalController;
use App\Http\Controllers\Surat\DisposisiController;
use App\Http\Controllers\Surat\SuratMasukController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    // login dan logout
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // timeline
    Route::get('/surat-masuk/{surat_masuk}/timeline', [SuratMasukController::class, 'timeline']);

    // daftar pegawai untuk pilihan tujuan disposisi (pimpinan)
    Route::get('/users/opsi-disposisi', [UserController::class, 'opsiDisposisi']);

    // master aplikasi (hanya admin)
    Route::middleware('role:Admin')->group(function () {
        Route::apiResource('bidangs', BidangController::class);
        Route::apiResource('jenis-surat', JenisSuratController::class);
        Route::apiResource('sifat-surat', SifatSuratController::class);
        Route::apiResource('users', UserController::class);
    });

    // persuratan
    Route::get('/surat-masuk', [SuratMasukController::class, 'index']);
    Route::get('/surat-masuk/{surat_masuk}', [SuratMasukController::class, 'show']);
    Route::post('/surat-masuk', [SuratMasukController::class, 'store'])
        ->middleware('role:Admin');
    Route::put('/surat-masuk/{surat_masuk}', [SuratMasukController::class, 'update'])
        ->middleware('role:Admin');
    Route::delete('/surat-masuk/{surat_masuk}', [SuratMasukController::class, 'destroy'])
        ->middleware('role:Admin');

    Route::get('/disposisi', [DisposisiController::class, 'index']);
    Route::get('/disposisi/{disposisi}', [DisposisiController::class, 'show']);
    Route::post('/disposisi', [DisposisiController::class, 'store'])
        ->middleware('role:Pimpinan');
    Route::put('/disposisi/{disposisi}', [DisposisiController::class, 'update']);
    Route::delete('/disposisi/{disposisi}', [DisposisiController::class, 'destroy'])
        ->middleware('role:Admin');

    // arsip
    Route::get('/arsip-digital', [ArsipDigitalController::class, 'index']);
    Route::get('/arsip-digital/{arsipDigital}', [ArsipDigitalController::class, 'show']);
    Route::get('/arsip-digital/{arsipDigital}/download', [ArsipDigitalController::class, 'download']);
    Route::post('/arsip-digital', [ArsipDigitalController::class, 'store'])
        ->middleware('role:Admin');
    Route::delete('/arsip-digital/{arsipDigital}', [ArsipDigitalController::class, 'destroy'])
        ->middleware('role:Admin');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::get('/notifikasi/unread', [NotifikasiController::class, 'unread']);
    Route::put('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'read']);

    // log aktifitas & laporan
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index']);
    Route::get('/laporan/surat-masuk', [LaporanController::class, 'suratMasuk']);

    // profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});
