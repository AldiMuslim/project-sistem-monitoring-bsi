@extends('layouts.app')

@section('page_title', 'Tambah Admin Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#00676F] p-8 text-white">
            <h3 class="text-xl font-black">Registrasi Admin BSI</h3>
            <p class="text-xs text-teal-100 font-bold uppercase tracking-widest mt-1">Input data pengguna baru sistem inventory</p>
        </div>
        
        <form action="{{ route('pengguna.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full mt-2 px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#F2A900] focus:outline-none font-bold text-sm" placeholder="Masukkan nama..." required>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Alamat Email</label>
                <input type="email" name="email" class="w-full mt-2 px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#F2A900] focus:outline-none font-bold text-sm" placeholder="email@bsi.co.id" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Password</label>
                <input type="password" name="password" class="w-full mt-2 px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#F2A900] focus:outline-none font-bold text-sm" placeholder="********" required>
            </div>

            <div class="flex gap-4 pt-4">
                <a href="/pengguna" class="flex-1 text-center py-4 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition">Batal</a>
                <button type="submit" class="flex-[2] bg-[#F2A900] hover:bg-[#d99800] text-gray-900 py-4 rounded-2xl font-black text-xs transition shadow-lg uppercase tracking-widest">
                    Simpan Data Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection