<?php

use App\Http\Controllers\ArmadaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BiroController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Data Master Routes
    Route::resource('biro', BiroController::class);
    Route::resource('destinasi', DestinasiController::class);
    Route::resource('armada', ArmadaController::class);
    Route::post('/armada/{po}/kendaraan', [ArmadaController::class, 'storeKendaraan'])->name('armada.kendaraan.store');
    Route::delete('/armada/kendaraan/{kendaraan}', [ArmadaController::class, 'destroyKendaraan'])->name('armada.kendaraan.destroy');
    Route::post('/armada/{po}/supir', [ArmadaController::class, 'storeSupir'])->name('armada.supir.store');
    Route::delete('/armada/supir/{supir}', [ArmadaController::class, 'destroySupir'])->name('armada.supir.destroy');
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('peserta', PesertaController::class);

    // Sistem Operasional Trip
    Route::resource('trip', TripController::class);
    Route::patch('/manifest/{manifest}/presensi', [TripController::class, 'togglePresensi'])->name('manifest.presensi');

    // Sub-modul Operasional Trip (Armada, Jadwal Acara, Manifest)
    Route::post('/trip/{trip}/armada', [TripController::class, 'storeArmada'])->name('trip.armada.store');
    Route::delete('/trip/armada/{armada}', [TripController::class, 'destroyArmada'])->name('trip.armada.destroy');

    Route::post('/trip/{trip}/jadwal', [TripController::class, 'storeJadwal'])->name('trip.jadwal.store');
    Route::delete('/trip/jadwal/{jadwal}', [TripController::class, 'destroyJadwal'])->name('trip.jadwal.destroy');

    Route::post('/trip/{trip}/manifest', [TripController::class, 'storeManifest'])->name('trip.manifest.store');
    Route::delete('/trip/manifest/{manifest}', [TripController::class, 'destroyManifest'])->name('trip.manifest.destroy');

    // User Management
    Route::resource('users', UserController::class);
});
