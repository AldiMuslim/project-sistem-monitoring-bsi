<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\LoginNotification;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        return view('login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // PROSES KIRIM EMAIL SAAT LOGIN
            auth()->user()->notify(new LoginNotification());

            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password tidak sesuai.']);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'jabatan' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 1. Simpan data user baru ke variabel $user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'password' => Hash::make($request->password),
        ]);

        // 2. LANGSUNG KIRIM EMAIL NOTIFIKASI SETELAH DAFTAR
        $user->notify(new LoginNotification());

        return redirect()->route('login')->with('success', 'Registrasi Berhasil! Silakan cek email kamu.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
}