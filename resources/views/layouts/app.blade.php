<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSI Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Animasi halus untuk notifikasi */
        .notification-fade {
            transition: all 0.5s ease;
        }
    </style>
</head>

<body class="bg-[#F4F7F6] h-screen flex overflow-hidden">

    <aside class="w-64 bg-[#00676F] text-white flex flex-col shadow-xl">
        <div class="p-6 flex items-center gap-3 border-b border-teal-800">
            <div class="bg-white p-1 rounded-lg">
                <h1 class="text-[#00676F] font-black italic text-xl">BSI</h1>
            </div>
            <div class="leading-tight">
                <p class="text-[10px] font-bold uppercase tracking-tighter">Bank Syariah</p>
                <p class="text-[10px] font-light uppercase tracking-tighter">Indonesia</p>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-xl {{ Request::is('dashboard') ? 'bg-teal-800/50' : '' }} hover:bg-[#F2A900] hover:text-gray-900 transition font-medium group">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            {{-- PERBAIKAN: Mengarahkan ke rute dan seleksi aktif persediaan --}}
            <a href="{{ route('persediaan.index') }}"
                class="flex items-center gap-3 p-3 rounded-xl {{ Request::is('persediaan*') ? 'bg-teal-800/50' : '' }} hover:bg-[#F2A900] hover:text-gray-900 transition font-medium">
                <i class="fas fa-box w-5 text-center"></i> Data Persediaan
            </a>

            <a href="{{ route('transaksi.index') }}"
                class="flex items-center gap-3 p-3 rounded-xl {{ Request::is('transaksi*') ? 'bg-teal-800/50' : '' }} hover:bg-[#F2A900] hover:text-gray-900 transition font-medium">
                <i class="fas fa-exchange-alt w-5 text-center"></i> Transaksi
            </a>
            <a href="{{ route('laporan.index') }}"
                class="flex items-center gap-3 p-3 rounded-xl {{ Request::is('laporan*') ? 'bg-teal-800/50' : '' }} hover:bg-[#F2A900] hover:text-gray-900 transition font-medium">
                <i class="fas fa-file-alt w-5 text-center"></i> Laporan
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white p-4 shadow-sm flex justify-between items-center px-8 border-b">
            <div>
                <h2 class="text-xl font-bold text-gray-800">@yield('page_title')</h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Monitoring Persediaan Kartu ATM
                    & Buku Tabungan</p>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center space-x-3 bg-gray-50 hover:bg-gray-100 transition px-3 py-1.5 rounded-full border border-gray-100 shadow-sm focus:outline-none">
                    <span class="text-[11px] font-black text-gray-700 hidden md:block">
                        {{ auth()->user()->name }} ({{ strtoupper(auth()->user()->jabatan) }})
                    </span>
                    <div
                        class="w-8 h-8 bg-[#00676F] rounded-full flex items-center justify-center text-white text-xs shadow-md">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">

                    {{-- Hanya tampil jika User adalah Admin --}}
                    @can('manage-users')
                        <a href="/pengguna"
                            class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50 hover:text-teal-700 transition">
                            <i class="fas fa-users-cog w-4"></i> Kelola Pengguna
                        </a>
                    @endcan

                    {{-- Link Profil diperbaiki agar sinkron dengan rute --}}
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50 hover:text-teal-700 transition">
                        <i class="fas fa-user-cog w-4"></i> Profil & Settings
                    </a>

                    <hr class="my-2 border-gray-50">

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-4 py-2 text-xs font-black text-red-500 hover:bg-red-50 transition w-full text-left">
                            <i class="fas fa-sign-out-alt w-4"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-8 overflow-y-auto">
            {{-- Notifikasi Global dengan ID untuk Auto-Hide --}}
            @if (session('success'))
                <div id="alert-msg"
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm font-bold shadow-sm notification-fade">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="alert-msg"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm font-bold shadow-sm notification-fade">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('alert-msg');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 3000); // Hilang dalam 3 detik
            }
        });
    </script>
</body>

</html>
