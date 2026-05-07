@extends('layouts.app')

@section('page_title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#00676F] p-10 text-white flex items-center gap-6">
            <div class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center text-3xl font-black border-4 border-white/30">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-2xl font-black">{{ Auth::user()->name }}</h3>
                <p class="text-teal-100 font-bold uppercase tracking-widest text-xs">{{ Auth::user()->jabatan ?? 'Administrator' }}</p>
            </div>
        </div>

        <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Email Terdaftar</label>
                <div class="mt-2 px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl font-bold text-gray-700">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Status Akun</label>
                <div class="mt-2 px-6 py-4 bg-green-50 border border-green-100 rounded-2xl font-bold text-green-600 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Aktif dalam Sistem
                </div>
            </div>

            <div class="md:col-span-2 pt-6 border-t border-gray-50 flex justify-end">
                <a href="{{ route('pengguna.edit', Auth::user()->id) }}" class="bg-[#F2A900] hover:bg-[#d99800] text-gray-900 px-8 py-4 rounded-2xl font-black text-xs transition shadow-lg uppercase tracking-widest">
                    Edit Profil & Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection