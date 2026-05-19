@extends('layouts.app')
@section('page_title', 'Transaksi')

@section('content')
    <div class="space-y-8 p-4 md:p-2">

        {{-- Bagian Atas: Input Transaksi Baru --}}
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Input Transaksi Baru</h3>

            <form action="{{ route('transaksi.store') }}" method="POST"
                class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nama Barang</label>
                    <select name="barang_id"
                        class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm focus:ring-2 focus:ring-[#00676F] font-bold cursor-pointer"
                        required>
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok: {{ $b->stok }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Jenis</label>
                    <select name="jenis_transaksi"
                        class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold cursor-pointer" required>
                        <option value="MASUK" class="text-green-600 font-bold">MASUK (+)</option>
                        <option value="KELUAR" class="text-red-600 font-bold">KELUAR (-)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Jumlah</label>
                    <input type="number" name="jumlah" min="1"
                        class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold" placeholder="50" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm text-gray-500 font-bold" required>
                </div>

                <button type="submit"
                    class="bg-[#00676F] text-white py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-teal-900/10 hover:bg-teal-800 transition">
                    Simpan Transaksi
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-[30px] border border-gray-100 shadow-sm max-w-3xl">
            <form action="{{ route('transaksi.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-[2]">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari barang atau nama petugas..."
                        class="w-full bg-gray-50 border-none rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-[#00676F] font-medium text-gray-700">
                </div>
                <div class="flex-1">
                    <select name="jenis_filter"
                        class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] cursor-pointer">
                        <option value="">Semua Riwayat</option>
                        <option value="MASUK" {{ request('jenis_filter') == 'MASUK' ? 'selected' : '' }}>Masuk (+)</option>
                        <option value="KELUAR" {{ request('jenis_filter') == 'KELUAR' ? 'selected' : '' }}>Keluar (-)
                        </option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-[#00676F] text-white px-5 rounded-xl font-bold text-sm hover:bg-teal-800 transition">Filter</button>
                    @if (request('search') || request('jenis_filter'))
                        <a href="{{ route('transaksi.index') }}"
                            class="bg-gray-100 text-gray-500 px-4 rounded-xl font-bold text-sm flex items-center justify-center hover:bg-gray-200 transition">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Bagian Bawah: Data Transaksi Terkini --}}
        <div id="tabel-log-transaksi" class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest">Data Transaksi Terkini</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] text-gray-400 uppercase tracking-[0.2em] border-b">
                            <th class="py-5 px-8 text-center">No</th>
                            <th class="py-5">Tanggal</th>
                            <th class="py-5">Barang</th>
                            <th class="py-5 text-center">Jenis</th>
                            <th class="py-5 text-center">Jumlah</th>
                            <th class="py-5">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transaksi as $index => $t)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-5 px-8 font-black text-center text-[#00676F]">
                                    {{ $transaksi->firstItem() + $index }}
                                </td>
                                <td class="py-5 text-xs text-gray-400 font-bold">
                                    {{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="py-5 font-bold text-gray-700">{{ $t->nama_barang }}</td>
                                <td class="py-5 text-center">
                                    @if ($t->jenis == 'MASUK')
                                        <span
                                            class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-[9px] font-black uppercase">Masuk</span>
                                    @else
                                        <span
                                            class="bg-rose-50 text-rose-700 border border-rose-200 px-3 py-1 rounded-full text-[9px] font-black uppercase">Keluar</span>
                                    @endif
                                </td>
                                <td
                                    class="py-5 text-center font-black text-sm {{ $t->jenis == 'MASUK' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $t->jenis == 'MASUK' ? '+' : '-' }}{{ number_format($t->jumlah) }}
                                </td>
                                <td class="py-5 text-xs font-bold text-gray-500 uppercase tracking-tight">
                                    {{ $t->petugas ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="py-12 text-center text-gray-400 font-bold uppercase text-xs tracking-widest">
                                    <i class="fa-solid fa-clock-rotate-left block text-2xl mb-2 text-gray-300"></i> Belum
                                    ada data transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-gray-50/30 border-t border-gray-50 bsi-pagination-wrapper">
                {{ $transaksi->links() }}
            </div>
        </div>
    </div>

    {{-- STYLE KUSTOM ANTI-BAYANGAN GELAP --}}
    <style>
        /* Mematikan paksa cincin fokus gelap/shadow bawaan tailwind saat diklik */
        .bsi-pagination-wrapper *,
        .bsi-pagination-wrapper a,
        .bsi-pagination-wrapper button,
        .bsi-pagination-wrapper span {
            box-shadow: none !important;
            outline: none !important;
            border-image: none !important;
        }

        .bsi-pagination-wrapper .relative.z-0 {
            box-shadow: none !important;
            display: inline-flex !important;
            gap: 6px !important;
        }

        /* Desain dasar kapsul premium */
        .bsi-pagination-wrapper a,
        .bsi-pagination-wrapper span[aria-current="page"] span,
        .bsi-pagination-wrapper .relative.z-0>span,
        .bsi-pagination-wrapper .relative.z-0>a {
            border-radius: 14px !important;
            border: 1px solid #f1f5f9 !important;
            padding: 10px 16px !important;
            font-weight: 800 !important;
            font-size: 12px !important;
            color: #475569 !important;
            background-color: #ffffff !important;
            transition: all 0.2s ease-in-out !important;
        }

        /* Halaman Aktif */
        .bsi-pagination-wrapper span[aria-current="page"] span {
            background-color: #00676F !important;
            color: #ffffff !important;
            border-color: #00676F !important;
        }

        /* Efek Sorot Hover */
        .bsi-pagination-wrapper a:hover {
            background-color: #e6f3f4 !important;
            color: #00676F !important;
            border-color: #00676F !important;
        }

        /* MENGHILANGKAN EFEK KOTAK GELAP SAAT DIKLIK (Penting!) */
        .bsi-pagination-wrapper a:focus,
        .bsi-pagination-wrapper a:active,
        .bsi-pagination-wrapper a:focus-visible,
        .bsi-pagination-wrapper svg:focus {
            background-color: #ffffff !important;
            color: #475569 !important;
            border-color: #f1f5f9 !important;
            outline: none !important;
            box-shadow: none !important;
        }

        /* Warna panah disabled */
        .bsi-pagination-wrapper span[aria-disabled="true"] span {
            background-color: #f8fafc !important;
            color: #cbd5e1 !important;
            border-color: #f1f5f9 !important;
            cursor: not-allowed !important;
        }

        .bsi-pagination-wrapper p {
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            font-weight: 700 !important;
            color: #94a3b8 !important;
        }
    </style>

    {{-- FIX SCRIPT: REKAM & KUNCI SCROLL INTERNAL WADAH <main> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Menemukan elemen kotak <main> tempat guliran halaman Anda berada
            const kotakMain = document.querySelector('main');

            if (kotakMain) {
                // 1. Ambil catatan tinggi guliran internal <main> jika ada
                const tinggiTerakhir = sessionStorage.getItem('guliran_main_bsi');
                if (tinggiTerakhir) {
                    // Setel posisi scroll <main> kembali ke titik semula
                    kotakMain.scrollTop = parseInt(tinggiTerakhir);
                    sessionStorage.removeItem('guliran_main_bsi');
                }

                // 2. Rekam posisi koordinat tinggi <main> saat tombol paginasi ditekan
                document.addEventListener("click", function(e) {
                    const keklikPaginasi = e.target.closest('.bsi-pagination-wrapper a');
                    if (keklikPaginasi) {
                        sessionStorage.setItem('guliran_main_bsi', kotakMain.scrollTop);
                    }
                });
            }
        });
    </script>
@endsection
