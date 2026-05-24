<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersediaanController; // 1. Diubah dari BarangController
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
    // Menggunakan PersediaanController untuk memproses data dashboard terbaru
    Route::get('/dashboard', [PersediaanController::class, 'dashboard'])->name('dashboard');

    // 2. MANAJEMEN PENGGUNA (Akses: Khusus Admin)
    Route::resource('pengguna', UserController::class)->middleware('can:manage-users');

    // 3. MANAJEMEN PERSEDIAAN (Akses: Campuran)
    // URL diubah menjadi /persediaan dan nama rute menjadi persediaan.index
    Route::get('/persediaan', [PersediaanController::class, 'index'])->name('persediaan.index');

    // Proteksi Modifikasi Persediaan (Akses: Khusus Admin)
    Route::middleware(['can:manage-users'])->group(function () {
        Route::post('/persediaan', [PersediaanController::class, 'store'])->name('persediaan.store');
        Route::put('/persediaan/update/{id}', [PersediaanController::class, 'update'])->name('persediaan.update');
        Route::delete('/persediaan/{id}', [PersediaanController::class, 'destroy'])->name('persediaan.destroy');
    });

    // 4. TRANSAKSI (Akses: Admin & Petugas)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

    // 5. LAPORAN & CETAK (Akses: Admin & Petugas)
    Route::get('/laporan', [TransaksiController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan/cetak', [TransaksiController::class, 'cetakPdf'])->name('laporan.cetak');

    // 6. PROFILE SETTINGS
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
