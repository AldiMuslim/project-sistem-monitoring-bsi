@extends('layouts.app')
@section('page_title', 'Dashboard')

@section('content')
    {{-- Bagian Atas: Ringkasan Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card Total Barang --}}
        <div
            class="bg-gradient-to-br from-blue-500 to-blue-700 p-6 rounded-[32px] text-white shadow-lg shadow-blue-200/50 flex justify-between items-center overflow-hidden relative group hover:scale-[1.02] transition-transform duration-300">
            <div class="z-10">
                <p class="text-xs font-bold uppercase tracking-wider opacity-80">Total Barang</p>
                <h3 class="text-4xl font-black my-2">{{ $totalJenisBarang }}</h3>
                <span class="text-[10px] bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full font-bold">Jenis Barang</span>
            </div>
            <i
                class="fas fa-box text-7xl absolute -right-4 opacity-10 group-hover:rotate-12 transition-transform duration-500"></i>
        </div>

        {{-- Card Total Stok --}}
        <div
            class="bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 rounded-[32px] text-white shadow-lg shadow-emerald-200/50 flex justify-between items-center overflow-hidden relative group hover:scale-[1.02] transition-transform duration-300">
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
            class="bg-gradient-to-br from-amber-400 to-amber-600 p-6 rounded-[32px] text-white shadow-lg shadow-amber-200/50 flex justify-between items-center overflow-hidden relative group hover:scale-[1.02] transition-transform duration-300">
            <div class="z-10">
                <p class="text-xs font-bold uppercase tracking-wider opacity-80">Transaksi Hari Ini</p>
                <h3 class="text-4xl font-black my-2">{{ $transaksiHariIni }}</h3>
                <span class="text-[10px] bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full font-bold">Aktivitas
                    Baru</span>
            </div>
            <i
                class="fas fa-exchange-alt text-7xl absolute -right-4 opacity-10 group-hover:rotate-12 transition-transform duration-500"></i>
        </div>

        {{-- Card Stok Menipis --}}
        <div
            class="bg-gradient-to-br from-rose-500 to-rose-700 p-6 rounded-[32px] text-white shadow-lg shadow-rose-200/50 flex justify-between items-center overflow-hidden relative group hover:scale-[1.02] transition-transform duration-300">
            <div class="z-10">
                <p class="text-xs font-bold uppercase tracking-wider opacity-80">Stok Menipis</p>
                <h3 class="text-4xl font-black my-2">{{ $stokMenipisCount }}</h3>
                <span
                    class="text-[10px] bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full font-bold text-yellow-200 animate-pulse">Butuh
                    Perhatian</span>
            </div>
            <i
                class="fas fa-exclamation-triangle text-7xl absolute -right-4 opacity-10 group-hover:rotate-12 transition-transform duration-500"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Bagian Kiri: Visualisasi Grafik --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h3 class="font-black text-gray-800 text-lg">Analisis Persediaan</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Pergerakan stok 7 hari terakhir
                    </p>
                </div>
                <div class="flex gap-4 p-2 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="flex items-center gap-2 text-[10px] font-black text-teal-600"><i
                            class="fas fa-circle text-[6px]"></i> KARTU ATM</span>
                    <span class="flex items-center gap-2 text-[10px] font-black text-amber-500"><i
                            class="fas fa-circle text-[6px]"></i> BUKU TABUNGAN</span>
                </div>
            </div>

            <div class="h-72 flex items-end gap-3 px-2 border-b border-gray-100 relative group">
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <span class="text-[10px] font-black text-gray-200 uppercase tracking-[0.5em] rotate-12">BSI Inventory
                        System</span>
                </div>

                {{-- Data Grafik Asli dari Database --}}
                @foreach ($chartData as $data)
                    <div class="flex-1 flex flex-col justify-end h-full gap-2 items-center">
                        <div class="flex w-full items-end justify-center gap-1 h-full">
                            {{-- Batang Kartu ATM --}}
                            <div class="w-full bg-teal-600 rounded-t-md hover:scale-y-110 hover:bg-teal-500 transition-all duration-300 shadow-md relative group/tooltip cursor-pointer"
                                style="height: {{ $data['atm'] }}%">
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-teal-700 opacity-0 group-hover/tooltip:opacity-100 transition-opacity">
                                    {{ $data['asli_atm'] }}
                                </span>
                            </div>

                            {{-- Batang Buku Tabungan --}}
                            <div class="w-full bg-amber-500 rounded-t-md hover:scale-y-110 hover:bg-amber-400 transition-all duration-300 shadow-md relative group/tooltip cursor-pointer"
                                style="height: {{ $data['buku'] }}%">
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-amber-600 opacity-0 group-hover/tooltip:opacity-100 transition-opacity">
                                    {{ $data['asli_buku'] }}
                                </span>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-gray-400 uppercase mt-1">{{ $data['hari'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Bagian Kanan: Aktivitas Terbaru --}}
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100 flex flex-col h-full">
            <div class="flex justify-between items-center mb-8">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Aktivitas Terkini</h3>
                {{-- PERBAIKAN: Mengubah 'transaksi' menjadi 'transaksi.index' --}}
                <a href="{{ route('transaksi.index') }}"
                    class="text-[10px] bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-black hover:bg-blue-600 hover:text-white transition-colors duration-300">SEMUA</a>
            </div>

            <div class="space-y-5 overflow-y-auto pr-2 custom-scrollbar">
                @forelse($recentTransaksi as $rt)
                    <div
                        class="flex items-center gap-4 group p-2 hover:bg-gray-50 rounded-2xl transition-all duration-300 border-b border-gray-50 last:border-0">
                        {{-- PERBAIKAN: Menggunakan $rt->jenis agar sesuai dengan DatabaseSeeder --}}
                        <div
                            class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-sm {{ strtoupper($rt->jenis) == 'MASUK' ? 'bg-green-50 text-green-600' : 'bg-rose-50 text-rose-600' }}">
                            <i
                                class="fas {{ strtoupper($rt->jenis) == 'MASUK' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-xs"></i>
                        </div>
                        <div class="flex-1">
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
                                class="text-[10px] font-black {{ strtoupper($rt->jenis) == 'MASUK' ? 'text-green-600' : 'text-rose-600' }}">
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
        }
    </style>
@endsection
