<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => 'Administrator', // Nilai default saat tambah baru
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Admin berhasil ditambah!');
    }

    // FUNGSI INI YANG MENYEBABKAN ERROR JIKA HILANG
    public function edit(User $user)
    {
        // Proteksi: Jika bukan admin, tendang balik ke dashboard
        if (auth()->user()->jabatan !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Proteksi: Hanya admin yang bisa update
        if (auth()->user()->jabatan !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'jabatan' => 'required',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')->with('success', 'Data petugas berhasil diubah.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('pengguna.index')->with('success', 'Admin dihapus!');
    }
}
