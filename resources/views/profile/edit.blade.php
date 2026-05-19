@extends('layouts.app')

@section('content')
    <div class="p-8 max-w-5xl mx-auto">

        <div class="mb-8">
            <h1 class="text-2xl font-black text-[#00676F]">Pengaturan Akun</h1>
            <p class="text-sm text-gray-400">Kelola informasi profil pribadi dan keamanan password Anda</p>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-2xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> Informasi profil Anda berhasil diperbarui!
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-2xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-lock-open"></i> Password Anda berhasil diubah!
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div
                class="bg-white rounded-[35px] p-8 shadow-sm border border-gray-100 h-fit text-center relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-3 bg-gradient-to-r from-[#00676F] to-[#00A499]"></div>

                <div
                    class="w-24 h-24 bg-teal-50 border-4 border-teal-600/20 text-[#00676F] rounded-full mx-auto flex items-center justify-center text-3xl font-black shadow-inner mb-4 mt-2">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>

                <h2 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                <p class="text-xs text-gray-400 font-medium mb-4">{{ auth()->user()->email }}</p>

                <span
                    class="inline-flex items-center text-[10px] px-4 py-1 rounded-full font-black tracking-widest border uppercase {{ auth()->user()->jabatan === 'admin' ? 'bg-teal-50 text-teal-700 border-teal-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                    <i class="fa-solid fa-shield text-[8px] mr-1.5"></i>{{ auth()->user()->jabatan ?? 'Petugas' }}
                </span>

                <div class="border-t border-gray-50 mt-6 pt-4 text-left space-y-2">
                    <div class="flex justify-between text-xs font-medium">
                        <span class="text-gray-400">ID Pengguna</span>
                        <span class="text-gray-700 font-bold">#{{ auth()->user()->id }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-medium">
                        <span class="text-gray-400">Bergabung Sejak</span>
                        <span
                            class="text-gray-700 font-bold">{{ auth()->user()->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="md:grid-cols-1 md:col-span-2 space-y-8">

                <div class="bg-white rounded-[35px] p-8 shadow-sm border border-gray-100">
                    <h3 class="text-base font-black text-gray-800 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <i class="fa-regular fa-user text-[#00676F]"></i> Detail Profil Pribadi
                    </h3>

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('patch')

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                autocomplete="name"
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-gray-700 mt-1">
                            @if ($errors->get('name'))
                                <p class="text-red-500 text-xs mt-1 ml-2">{{ $errors->get('name')[0] }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Alamat
                                Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                autocomplete="username"
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-gray-700 mt-1">
                            @if ($errors->get('email'))
                                <p class="text-red-500 text-xs mt-1 ml-2">{{ $errors->get('email')[0] }}</p>
                            @endif
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="bg-[#00676F] text-white px-8 py-3.5 rounded-2xl font-bold uppercase text-xs tracking-widest shadow-md hover:bg-teal-800 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-[35px] p-8 shadow-sm border border-gray-100 border-b-[6px] border-[#F2A900]">
                    <h3 class="text-base font-black text-gray-800 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-key text-[#F2A900]"></i> Perbarui Keamanan Password
                    </h3>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('put')

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Password
                                Lama</label>
                            <input type="password" name="current_password" placeholder="••••••••"
                                autocomplete="current-password" required
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-gray-700 mt-1">
                            @if ($errors->updatePassword->get('current_password'))
                                <p class="text-red-500 text-xs mt-1 ml-2">
                                    {{ $errors->updatePassword->get('current_password')[0] }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Password
                                Baru</label>
                            <input type="password" name="password" placeholder="Min. 8 Karakter" autocomplete="new-password"
                                required
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-gray-700 mt-1">
                            @if ($errors->updatePassword->get('password'))
                                <p class="text-red-500 text-xs mt-1 ml-2">{{ $errors->updatePassword->get('password')[0] }}
                                </p>
                            @endif
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Ulangi
                                Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••"
                                autocomplete="new-password" required
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-gray-700 mt-1">
                            @if ($errors->updatePassword->get('password_confirmation'))
                                <p class="text-red-500 text-xs mt-1 ml-2">
                                    {{ $errors->updatePassword->get('password_confirmation')[0] }}</p>
                            @endif
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="bg-gradient-to-r from-[#F2A900] to-[#D19200] text-[#003335] px-8 py-3.5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-md hover:brightness-110 transition">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
