@extends('layouts.app')
@section('page_title', 'Dashboard')

@section('content')
    {{-- Bagian Atas: Sapaan Dinamis Islami & Jam Digital Detik Berjalan --}}
    <div x-data="{
        time: '',
        date: '',
        init() {
            this.updateTime();
            setInterval(() => this.updateTime(), 1000);
        },
        updateTime() {
            const now = new Date();
            this.time = now.toLocaleTimeString('id-ID', { hour12: false });
            this.date = now.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
        }
    }"
        class="mb-8 bg-gradient-to-br from-white to-teal-50/30 p-8 rounded-[40px] border border-teal-100/40 flex justify-between items-center relative overflow-hidden shadow-sm">

        {{-- Ornamen Geometris --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.04] text-[#00676F] z-0">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="islamicPattern" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M30 0 L60 30 L30 60 L0 30 Z" fill="none" stroke="currentColor" stroke-width="1.5" />
                        <path d="M0 0 L60 60 M60 0 L0 60" fill="none" stroke="currentColor" stroke-width="1" />
                        <circle cx="30" cy="30" r="10" fill="none" stroke="currentColor"
                            stroke-width="1.5" />
                        <polygon points="30,5 35,20 50,20 38,30 43,45 30,35 17,45 22,30 10,20 25,20" fill="none"
                            stroke="currentColor" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#islamicPattern)" />
            </svg>
        </div>

        <div class="z-10">
            @php
                $hour = (int) date('H');
                $greetings = 'Selamat Malam';
                $icon = 'fa-solid fa-moon text-indigo-400';

                if ($hour >= 4 && $hour < 11) {
                    $greetings = 'Selamat Pagi';
                    $icon = 'fa-solid fa-sun text-amber-500 animate-pulse';
                } elseif ($hour >= 11 && $hour < 15) {
                    $greetings = 'Selamat Siang';
                    $icon = 'fa-solid fa-cloud-sun text-orange-400 animate-pulse';
                } elseif ($hour >= 15 && $hour < 18) {
                    $greetings = 'Selamat Sore';
                    $icon = 'fa-solid fa-cloud text-teal-600 animate-pulse';
                }
            @endphp

            <h1 class="text-2xl font-black text-[#00676F] flex flex-wrap items-center gap-x-3 gap-y-2">
                <span>Assalamualaikum, {{ auth()->user()->name }}</span>
                <span class="text-gray-200 hidden sm:inline">|</span>
                <span
                    class="text-xs font-black text-gray-600 flex items-center gap-1.5 bg-gray-50/80 backdrop-blur-sm px-3 py-1.5 rounded-xl border border-gray-100">
                    <i class="{{ $icon }} text-xs"></i> {{ $greetings }}
                </span>
            </h1>

            <div class="flex items-center gap-4 mt-3">
                <p class="text-[10px] text-gray-400 font-medium flex items-center gap-2">
                    <i class="fa-regular fa-building text-teal-600"></i> BSI Inventory KCP Padang Ulak Karang
                </p>
                <span class="text-gray-200">•</span>
                {{-- JAM DIGITAL BERJALAN --}}
                <div class="flex items-center gap-2 bg-[#00676F] text-white px-3 py-1 rounded-lg shadow-sm">
                    <i class="fa-regular fa-clock text-[10px]"></i>
                    <span x-text="time" class="text-xs font-black tracking-widest leading-none mt-0.5"></span>
                </div>
            </div>

            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mt-2.5" x-text="date"></p>
        </div>

        <div class="hidden md:block text-right z-10">
            <span
                class="text-[9px] font-black text-[#00676F] bg-teal-50/60 backdrop-blur-sm border border-teal-200/40 px-4 py-3 rounded-2xl uppercase tracking-widest shadow-sm">
                <i class="fa-solid fa-mosque mr-1.5 text-teal-600"></i> Sahabat Finansial & Sosial
            </span>
        </div>
    </div>

    {{-- 2. BARIS TOMBOL PINTAS CEPAT --}}
    <div class="mb-8">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Akses Pintas Operasional</p>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <a href="{{ route('transaksi.index', ['jenis_filter' => 'MASUK']) }}"
                class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 group hover:border-emerald-300 hover:shadow-md transition duration-300">
                <div
                    class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-file-import text-sm"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-gray-800 uppercase tracking-tight">Logistik Masuk</h4>
                    <p class="text-[9px] text-gray-400 font-medium mt-0.5">Input restock persediaan baru</p>
                </div>
            </a>

            <a href="{{ route('transaksi.index', ['jenis_filter' => 'KELUAR']) }}"
                class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 group hover:border-rose-300 hover:shadow-md transition duration-300">
                <div
                    class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-file-export text-sm"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-gray-800 uppercase tracking-tight">Logistik Keluar</h4>
                    <p class="text-[9px] text-gray-400 font-medium mt-0.5">Input pengeluaran unit</p>
                </div>
            </a>

            {{-- PERBAIKAN 1: Rute diubah dari barang.index ke persediaan.index --}}
            <a href="{{ route('persediaan.index') }}"
                class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 group hover:border-blue-300 hover:shadow-md transition duration-300">
                <div
                    class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-warehouse text-sm"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-gray-800 uppercase tracking-tight">Stok Gudang</h4>
                    <p class="text-[9px] text-gray-400 font-medium mt-0.5">Audit master data fisik</p>
                </div>
            </a>

            @can('manage-users')
                <a href="{{ route('users.index') }}"
                    class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 group hover:border-amber-300 hover:shadow-md transition duration-300">
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition duration-300">
                        <i class="fa-solid fa-users-gear text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-gray-800 uppercase tracking-tight">Otorisasi Petugas</h4>
                        <p class="text-[9px] text-amber-600 font-bold mt-0.5 flex items-center gap-0.5"><i
                                class="fa-solid fa-shield text-[7px]"></i> Admin Only</p>
                    </div>
                </a>
            @else
                <a href="{{ route('laporan.index') }}"
                    class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 group hover:border-teal-300 hover:shadow-md transition duration-300">
                    <div
                        class="w-12 h-12 rounded-2xl bg-teal-50 text-[#00676F] flex items-center justify-center group-hover:bg-[#00676F] group-hover:text-white transition duration-300">
                        <i class="fa-solid fa-print text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-gray-800 uppercase tracking-tight">Cetak Rekap</h4>
                        <p class="text-[9px] text-gray-400 font-medium mt-0.5">Arsip bulanan kantor</p>
                    </div>
                </a>
            @endcan

        </div>
    </div>

    {{-- 3. Ringkasan Statistik Angka --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card Total Persediaan --}}
        <div
            class="bg-gradient-to-br from-blue-600 to-blue-800 p-6 rounded-[32px] text-white shadow-lg shadow-blue-200/50 flex justify-between items-center overflow-hidden relative group hover:scale-[1.02] transition-transform duration-300">
            <div class="z-10">
                <p class="text-xs font-bold uppercase tracking-wider opacity-80">Total Persediaan</p>
                {{-- PERBAIKAN 2: Variabel diubah menjadi $totalJenisPersediaan --}}
                <h3 class="text-4xl font-black my-2">{{ $totalJenisPersediaan }}</h3>
                <span class="text-[10px] bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full font-bold">Jenis Item</span>
            </div>
            <i
                class="fas fa-box text-7xl absolute -right-4 opacity-10 group-hover:rotate-12 transition-transform duration-500"></i>
        </div>

        {{-- Card Total Stok --}}
        <div
            class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-6 rounded-[32px] text-white shadow-lg shadow-emerald-200/50 flex justify-between items-center overflow-hidden relative group hover:scale-[1.02] transition-transform duration-300">
            <div class="z-10">
                <p class="text-xs font-bold uppercase tracking-wider opacity-80">Total Stok</p>
                <h3 class="text-4xl font-black my-2">{{ number_format($totalStok) }}</h3>
                <span class="text-[10px] bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full font-bold">Pcs Terdata</span>
            </div>
            <i
                class="fas fa-cubes text-7xl absolute -right-4 opacity-10 group-hover:rotate-12 transition-transform duration-500"></i>
        </div>

        {{-- Card Transaksi Hari Ini --}}
        <div
            class="bg-gradient-to-br from-teal-500 to-teal-700 p-6 rounded-[32px] text-white shadow-lg shadow-teal-200/50 flex justify-between items-center overflow-hidden relative group hover:scale-[1.02] transition-transform duration-300">
            <div class="z-10 w-full">
                <p class="text-xs font-bold uppercase tracking-wider opacity-80">Transaksi Hari Ini</p>
                <h3 class="text-4xl font-black my-2">{{ $transaksiHariIni }} <span
                        class="text-xs font-normal opacity-75">Log</span></h3>
                <div class="flex gap-2 text-[9px] font-black tracking-wider uppercase mt-1">
                    <span class="bg-emerald-500/30 backdrop-blur-sm px-2 py-0.5 rounded-md text-emerald-200">
                        Masuk: +{{ $itemMasukHariIni }}
                    </span>
                    <span class="bg-black/25 backdrop-blur-sm px-2 py-0.5 rounded-md text-teal-200">
                        Keluar: -{{ $itemKeluarHariIni }}
                    </span>
                </div>
            </div>
            <i
                class="fas fa-exchange-alt text-7xl absolute -right-4 opacity-10 group-hover:rotate-12 transition-transform duration-500"></i>
        </div>

        {{-- Card Stok Menipis --}}
        {{-- PERBAIKAN 3: Rute diubah ke persediaan.index --}}
        <a href="{{ route('persediaan.index', ['filter' => 'menipis']) }}"
            class="bg-gradient-to-br from-amber-700 to-stone-800 p-6 rounded-[32px] text-white shadow-lg shadow-amber-900/20 flex justify-between items-center overflow-hidden relative group hover:scale-[1.02] transition-transform duration-300 cursor-pointer">
            <div class="z-10">
                <p class="text-xs font-bold uppercase tracking-wider opacity-80">Stok Menipis</p>
                <h3 class="text-4xl font-black my-2">{{ $stokMenipisCount }}</h3>
                <span
                    class="text-[10px] bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full font-bold text-amber-200 animate-pulse flex items-center gap-1">
                    <i class="fa-solid fa-arrow-right text-[8px]"></i> Periksa Item
                </span>
            </div>
            <i
                class="fas fa-exclamation-triangle text-7xl absolute -right-4 opacity-10 group-hover:rotate-12 transition-transform duration-500 text-amber-400"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- 4. Bagian Kiri: Visualisasi Grafik Batang + Donat --}}
        <div
            class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h3 class="font-black text-gray-800 text-lg">Analisis Persediaan</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Pergerakan logistik 7 hari
                        terakhir</p>
                </div>
                <div class="flex flex-wrap gap-4 p-2 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="flex items-center gap-2 text-[10px] font-black text-emerald-600">
                        <i class="fas fa-circle text-[6px]"></i> ITEM MASUK
                    </span>
                    <span class="flex items-center gap-2 text-[10px] font-black text-[#00676F]">
                        <i class="fas fa-circle text-[6px]"></i> ITEM KELUAR
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <div class="md:col-span-2 h-72 flex items-end gap-3 px-2 border-b border-gray-100 relative group">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="text-[10px] font-black text-gray-100 uppercase tracking-[0.5em] rotate-12">
                            BSI Inventory System
                        </span>
                    </div>

                    @foreach ($chartData as $data)
                        <div class="flex-1 flex flex-col justify-end h-full gap-2 items-center">
                            <div class="flex w-full items-end justify-center gap-1 h-full">
                                <div class="w-full bg-emerald-500 rounded-t-md hover:scale-y-110 hover:bg-emerald-400 transition-all duration-300 shadow-sm relative group/tooltip cursor-pointer"
                                    style="height: {{ $data['masuk'] }}%">
                                    <span
                                        class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-bold text-emerald-600 opacity-0 group-hover/tooltip:opacity-100 transition-opacity whitespace-nowrap">
                                        +{{ $data['asli_masuk'] }}
                                    </span>
                                </div>
                                <div class="w-full bg-[#00676F] rounded-t-md hover:scale-y-110 hover:bg-teal-600 transition-all duration-300 shadow-sm relative group/tooltip cursor-pointer"
                                    style="height: {{ $data['keluar'] }}%">
                                    <span
                                        class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-bold text-teal-700 opacity-0 group-hover/tooltip:opacity-100 transition-opacity whitespace-nowrap">
                                        -{{ $data['asli_keluar'] }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-gray-400 uppercase mt-1">{{ $data['hari'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div
                    class="flex flex-col items-center justify-center p-5 bg-gray-50/50 rounded-[35px] border border-gray-100/70 h-full">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Proporsi Gudang</p>
                    <div class="relative w-36 h-36 rounded-full flex items-center justify-center shadow-inner transition-transform duration-500 hover:scale-105"
                        style="background: conic-gradient(#00676F 0% {{ $persenATM }}%, #F2A900 {{ $persenATM }}% 100%);">
                        <div class="w-26 h-26 bg-white rounded-full flex flex-col items-center justify-center shadow-sm">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Total Aset</span>
                            <span
                                class="text-xl font-black text-gray-800 tracking-tight">{{ number_format($stokATM + $stokBuku) }}</span>
                            <span class="text-[8px] font-bold text-gray-400 uppercase">Unit</span>
                        </div>
                    </div>
                    <div class="w-full mt-5 space-y-2">
                        <div
                            class="flex justify-between items-center text-[10px] font-bold text-gray-600 bg-white p-2 rounded-xl border border-gray-50">
                            <span class="flex items-center gap-1.5"><i
                                    class="fas fa-circle text-[#00676F] text-[7px]"></i> Kartu ATM</span>
                            <span class="font-black text-gray-800">{{ $persenATM }}% <span
                                    class="text-[8px] text-gray-400 font-normal">({{ number_format($stokATM) }})</span></span>
                        </div>
                        <div
                            class="flex justify-between items-center text-[10px] font-bold text-gray-600 bg-white p-2 rounded-xl border border-gray-50">
                            <span class="flex items-center gap-1.5"><i
                                    class="fas fa-circle text-[#F2A900] text-[7px]"></i> Buku Tabungan</span>
                            <span class="font-black text-gray-800">{{ $persenBuku }}% <span
                                    class="text-[8px] text-gray-400 font-normal">({{ number_format($stokBuku) }})</span></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 5. Bagian Kanan: Aktivitas Terbaru --}}
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100 flex flex-col h-full">
            <div class="flex justify-between items-center mb-8">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Aktivitas Terkini</h3>
                <a href="{{ route('transaksi.index') }}"
                    class="text-[10px] bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-black hover:bg-blue-600 hover:text-white transition-colors duration-300">SEMUA</a>
            </div>

            <div class="space-y-5 overflow-y-auto pr-2 custom-scrollbar">
                @forelse($recentTransaksi as $rt)
                    <div
                        class="flex items-center gap-4 group p-2 hover:bg-gray-50 rounded-2xl transition-all duration-300 border-b border-gray-50 last:border-0">
                        <div
                            class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-sm {{ strtoupper($rt->jenis) == 'MASUK' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            <i
                                class="fas {{ strtoupper($rt->jenis) == 'MASUK' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-xs"></i>
                        </div>
                        <div class="flex-1">
                            {{-- NB: Log mutasi histori transaksi tetap menggunakan properti database asli ($rt->nama_barang) --}}
                            <p
                                class="text-xs font-black text-gray-700 leading-tight group-hover:text-teal-700 transition-colors">
                                {{ Str::limit($rt->nama_barang, 20) }}
                            </p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">
                                {{ \Carbon\Carbon::parse($rt->created_at)->format('d M, H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p
                                class="text-[10px] font-black {{ strtoupper($rt->jenis) == 'MASUK' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ strtoupper($rt->jenis) == 'MASUK' ? '+' : '-' }}{{ $rt->jumlah }}
                            </p>
                            <p class="text-[8px] font-bold text-gray-300 uppercase">Unit</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-history text-gray-200 text-xl"></i>
                        </div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Belum ada aktivitas
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
@endsection
