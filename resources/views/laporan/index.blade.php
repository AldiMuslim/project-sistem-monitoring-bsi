@extends('layouts.app')
@section('page_title', 'Laporan Perpindahan')

@section('content')
    <div class="space-y-6">

        <div class="hidden-web-header border-b-4 border-[#00676F] pb-4 mb-6 items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-[#00676F] text-white p-2 rounded-xl text-center">
                    <h1 class="font-black italic text-xl leading-none">BSI</h1>
                </div>
                <div>
                    <h2 class="text-base font-black text-[#00676F] uppercase tracking-tight">PT Bank Syariah Indonesia, Tbk
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold">KCP PADANG ULAK KARANG • Jl. S. Parman No. 40, Kota Padang
                    </p>
                </div>
            </div>
            <div class="text-right">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Laporan Mutasi Logistik</h3>
                <p class="text-[10px] text-gray-400 font-bold">Periode Buku:
                    {{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') }} s/d
                    {{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') }}</p>
            </div>
        </div>

        <div class="no-print bg-white p-6 rounded-[35px] border border-gray-100 shadow-sm">
            <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col lg:flex-row items-end gap-4">

                <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" value="{{ $tglMulai }}"
                            class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-[#00676F]">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">Sampai
                            Tanggal</label>
                        <input type="date" name="tgl_selesai" value="{{ $tglSelesai }}"
                            class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-[#00676F]">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">Jenis
                            Pergerakan</label>
                        <select name="jenis"
                            class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold text-[#00676F] cursor-pointer focus:ring-2 focus:ring-[#00676F]">
                            <option value="">Semua Sirkulasi</option>
                            <option value="MASUK" {{ $jenis == 'MASUK' ? 'selected' : '' }}>Hanya Barang Masuk (+)</option>
                            <option value="KELUAR" {{ $jenis == 'KELUAR' ? 'selected' : '' }}>Hanya Barang Keluar (-)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit"
                        class="flex-1 lg:flex-none bg-gray-100 text-gray-700 px-6 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-wider hover:bg-gray-200 transition">
                        Saring Laporan
                    </button>
                    <button type="button" onclick="window.print()"
                        class="flex-1 lg:flex-none bg-[#00676F] text-white px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-teal-900/10 hover:bg-teal-800 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-print"></i> Cetak Dokumen
                    </button>
                </div>

            </form>
        </div>

        <div class="bg-white rounded-[35px] shadow-sm border border-gray-100 overflow-hidden print-border-none">
            <div class="no-print p-6 border-b border-gray-50 bg-gray-50/30">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Arsip Log Transaksi Terpilih</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left print-text-black">
                    <thead class="bg-gray-50/50 print-bg-gray border-b">
                        <tr class="text-[10px] uppercase tracking-widest text-gray-400 print-text-black">
                            <th class="p-5 text-center w-16">No</th>
                            <th class="p-5">Tanggal Buku</th>
                            <th class="p-5">Spesifikasi Item Logistik</th>
                            <th class="p-5 text-center">Jenis Mutasi</th>
                            <th class="p-5 text-center">Volume Satuan</th>
                            <th class="p-5">Petugas Input</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 print-divide">
                        @forelse ($laporan as $index => $l)
                            <tr class="hover:bg-gray-50/50 transition print-row-break">
                                <td class="p-5 text-center font-bold text-[#00676F] print-text-black">{{ $index + 1 }}
                                </td>
                                <td class="p-5 text-xs font-bold text-gray-500 print-text-black">
                                    {{ \Carbon\Carbon::parse($l->tanggal)->translatedFormat('d/m/Y') }}
                                </td>
                                <td class="p-5 font-bold text-gray-800 print-text-black">{{ $l->nama_barang }}</td>
                                <td class="p-5 text-center">
                                    @if (strtoupper($l->jenis) === 'MASUK')
                                        <span
                                            class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-0.5 rounded-full text-[9px] font-black uppercase print-badge-masuk">MASUK</span>
                                    @else
                                        <span
                                            class="bg-rose-50 text-rose-700 border border-rose-200 px-3 py-0.5 rounded-full text-[9px] font-black uppercase print-badge-keluar">KELUAR</span>
                                    @endif
                                </td>
                                <td
                                    class="p-5 text-center font-black text-sm {{ strtoupper($l->jenis) == 'MASUK' ? 'text-emerald-600' : 'text-rose-600' }} print-text-black">
                                    {{ strtoupper($l->jenis) == 'MASUK' ? '+' : '-' }}{{ number_format($l->jumlah) }}
                                </td>
                                <td class="p-5 text-xs text-gray-500 font-bold uppercase print-text-black">
                                    {{ $l->petugas ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="p-12 text-center text-sm font-bold text-gray-400 uppercase tracking-widest">
                                    <i class="fa-solid fa-folder-open block text-2xl mb-2 text-gray-300"></i> Tidak ada
                                    mutasi sirkulasi pada periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="hidden-print-footer grid grid-cols-2 mt-12 pt-8 text-xs font-bold text-center">
            <div>
                <p class="mb-16 text-gray-400 uppercase tracking-wider text-[9px]">Dibuat Oleh Piket,</p>
                <p class="text-gray-800 border-b border-gray-400 inline-block px-6 pb-1">{{ auth()->user()->name }}</p>
                <p class="text-[9px] text-gray-400 mt-1 uppercase">{{ auth()->user()->jabatan }} LOGISTIK</p>
            </div>
            <div>
                <p class="mb-16 text-gray-400 uppercase tracking-wider text-[9px]">Mengetahui,</p>
                <p class="text-gray-800 border-b border-gray-400 inline-block px-6 pb-1">___________________________</p>
                <p class="text-[9px] text-gray-400 mt-1 uppercase">Head of KCP Padang Ulak Karang</p>
            </div>
        </div>

    </div>

    {{-- MANIPULASI CSS PRINT VERSI PERBAIKAN TOTAL (ANTI-BOCOR SIDEBAR) --}}
    <style>
        .hidden-web-header,
        .hidden-print-footer {
            display: none;
        }

        @media print {

            /* 1. SELEKTOR KHUSUS: Bongkar paksa pembungkus utama tanpa merusak utilitas .flex global */
            html,
            body,
            body>div,
            main {
                display: block !important;
                position: static !important;
                overflow: visible !important;
                height: auto !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background-color: #ffffff !important;
                box-shadow: none !important;
            }

            /* 2. PROTEKSI ULTRA: Hancurkan dan sembunyikan sidebar serta header web sampai ke akar-akarnya */
            body aside,
            body header,
            .no-print,
            body aside *,
            body header * {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                width: 0 !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
            }

            /* 3. Tampilkan Kop Surat & Tanda Tangan Lapangan */
            .hidden-web-header {
                display: flex !important;
            }

            .hidden-print-footer {
                display: grid !important;
            }

            /* 4. Gaya Tabel Ramah Printer */
            .print-border-none {
                border: none !important;
                box-shadow: none !important;
            }

            .print-text-black {
                color: #000000 !important;
                font-size: 11px !important;
            }

            .print-bg-gray {
                background-color: #f8fafc !important;
                border-bottom: 2px solid #000000 !important;
            }

            .print-divide>tr {
                border-bottom: 1px solid #e2e8f0 !important;
            }

            .print-badge-masuk {
                background: #f0fdf4 !important;
                color: #166534 !important;
                border: 1px solid #bbf7d0 !important;
            }

            .print-badge-keluar {
                background: #fef2f2 !important;
                color: #991b1b !important;
                border: 1px solid #fecaca !important;
            }

            .print-row-break {
                page-break-inside: avoid !important;
            }
        }
    </style>
@endsection
