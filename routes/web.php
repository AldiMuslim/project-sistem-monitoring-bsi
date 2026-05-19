<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;

// Halaman Welcome
Route::get('/', function () {
    return view('welcome_bsi');
});

// Grouping Route yang memerlukan login (auth)
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. DASHBOARD (Akses: Admin & Petugas)
    Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('dashboard');

    // 2. MANAJEMEN PENGGUNA (Akses: Khusus Admin)
    Route::resource('pengguna', UserController::class)->middleware('can:manage-users');

    // 3. MANAJEMEN BARANG (Akses: Campuran)
    // Petugas hanya bisa melihat (Read-only)
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

    // Proteksi Modifikasi Barang (Akses: Khusus Admin)
    Route::middleware(['can:manage-users'])->group(function () {
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::put('/barang/update/{id}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    });

    // 4. TRANSAKSI (Akses: Admin & Petugas)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

    // 5. LAPORAN & CETAK (Akses: Admin & Petugas)
    // Dipindahkan ke TransaksiController agar fitur filter dan PDF berjalan sinkron
    Route::get('/laporan', [TransaksiController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan/cetak', [TransaksiController::class, 'cetakPdf'])->name('laporan.cetak');

    // 6. PROFILE SETTINGS
    // Menggunakan nama rute standar 'profile.edit' agar sinkron dengan layout
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // KUMPULAN RUTE KELOLA USER (USER MANAGEMENT)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

// Memuat rute autentikasi standar (login, logout, dll)
require __DIR__ . '/auth.php';
