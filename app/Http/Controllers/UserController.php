<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * TAMPILKAN HALAMAN KELOLA PENGGUNA (KHUSUS ADMIN)
     */
    public function index()
    {
        // Proteksi Gate: Hanya Admin yang bisa masuk
        Gate::authorize('manage-users');

        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * SIMPAN PENGGUNA BARU
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'jabatan' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
        ]);

        return back()->with('success', 'Pengguna baru berhasil didaftarkan!');
    }

    /**
     * UPDATE DATA PENGGUNA
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('manage-users');

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'jabatan' => 'required|string',
            'password' => 'nullable|string|min:8', // Password opsional saat edit
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
        ];

        // Jika password diisi, enkripsi dan masukkan ke array update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * HAPUS PENGGUNA
     */
    public function destroy($id)
    {
        Gate::authorize('manage-users');

        // Mencegah admin menghapus akun dirinya sendiri secara tidak sengaja
        if (auth()->user()->id == $id) {
            return back()->with('error', 'Akses Ditolak: Anda tidak bisa menghapus akun Anda sendiri!');
        }

        User::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna berhasil dihapus dari sistem!');
    }
}
