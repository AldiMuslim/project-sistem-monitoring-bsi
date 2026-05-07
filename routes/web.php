<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;

// 1. HALAMAN PUBLIK (Bisa diakses tanpa login)
Route::get('/', function () { 
    return view('welcome_bsi'); 
})->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', function () { return view('register'); })->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// 2. MENU UTAMA (WAJIB LOGIN)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('dashboard');

    // Data Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/update/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/destroy/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // Fitur Transaksi
    Route::get('/transaksi', [BarangController::class, 'transaksi'])->name('transaksi');
    Route::post('/transaksi', [BarangController::class, 'storeTransaksi'])->name('transaksi.store');

    // HALAMAN PROFIL
    Route::get('/profil', function () {
        return view('profile.index'); 
    })->name('profil');

    // LAPORAN
    Route::get('/laporan', [BarangController::class, 'laporan'])->name('laporan');

    // MANAJEMEN PENGGUNA
    Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/create', [UserController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [UserController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{id}/edit', [UserController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna/{id}', [UserController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->name('pengguna.destroy');

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});