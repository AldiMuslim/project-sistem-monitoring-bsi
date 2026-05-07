@extends('layouts.app')

@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-[32px] shadow-sm border border-gray-100">
        <div>
            <h3 class="text-xl font-black text-gray-800">Daftar Admin Sistem</h3>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Kelola hak akses dan akun pengguna BSI</p>
        </div>
        <button onclick="window.location.href='/pengguna/create'" class="bg-[#F2A900] hover:bg-[#d99800] text-gray-900 px-6 py-3 rounded-2xl font-black text-xs transition shadow-lg flex items-center gap-2 uppercase tracking-wider">
            <i class="fas fa-user-plus text-sm"></i> Tambah Pengguna
        </button>
    </div>

    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Pengguna</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    {{-- Loop data pengguna dari database --}}
                    {{-- Ganti $users dengan variabel dari Controller kamu --}}
                    @forelse($users ?? [] as $user)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-8 py-5">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-[#00676F] rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <p class="text-sm font-black text-gray-800">{{ $user->name }}</p>
            {{-- GANTI BARIS DI BAWAH INI --}}
            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $user->jabatan ?? 'User' }}</p>
        </div>
    </div>
</td>
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold text-gray-600 italic">{{ $user->email }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-full uppercase border border-green-100">Aktif</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex justify-center gap-2">
                                <a href="/pengguna/{{ $user->id }}/edit" class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="/pengguna/{{ $user->id }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center justify-center opacity-20">
                                <i class="fas fa-users text-5xl mb-3"></i>
                                <p class="text-xs font-black uppercase tracking-widest">Belum ada data pengguna</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection