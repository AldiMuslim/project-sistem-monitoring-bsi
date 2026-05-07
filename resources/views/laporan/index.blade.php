@extends('layouts.app')

@section('page_title', 'Laporan Transaksi')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
        <div class="flex flex-wrap md:flex-nowrap items-end gap-6">
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Dari Tanggal</label>
                <input type="date" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm text-gray-500 focus:ring-2 focus:ring-[#00676F]">
            </div>
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Sampai Tanggal</label>
                <input type="date" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm text-gray-500 focus:ring-2 focus:ring-[#00676F]">
            </div>
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Barang</label>
                <select class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#00676F]">
                    <option>Semua Barang</option>
                    <option>Kartu ATM BSI</option>
                    <option>Buku Tabungan BSI</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button class="bg-[#00A3AD] text-white px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-teal-600 transition flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <button class="bg-[#00676F] text-white px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-teal-800 transition flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/30">
            <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest">Riwayat Transaksi Persediaan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] text-gray-400 uppercase tracking-[0.2em] border-b">
                        <th class="py-5 px-8">No</th>
                        <th class="py-5">Tanggal</th>
                        <th class="py-5">Barang</th>
                        <th class="py-5">Jenis</th>
                        <th class="py-5">Jumlah</th>
                        <th class="py-5 px-8">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-5 px-8 font-black text-[#00676F]">1</td>
                        <td class="py-5 text-sm text-gray-500">24/04/2024</td>
                        <td class="py-5 font-bold text-gray-700">Kartu ATM BSI</td>
                        <td class="py-5">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase">Masuk</span>
                        </td>
                        <td class="py-5 font-black text-gray-800">50</td>
                        <td class="py-5 px-8 text-xs text-gray-400 italic">Stok awal</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-5 px-8 font-black text-[#00676F]">2</td>
                        <td class="py-5 text-sm text-gray-500">23/04/2024</td>
                        <td class="py-5 font-bold text-gray-700">Buku Tabungan</td>
                        <td class="py-5">
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[9px] font-black uppercase">Keluar</span>
                        </td>
                        <td class="py-5 font-black text-gray-800">10</td>
                        <td class="py-5 px-8 text-xs text-gray-400 italic">Diberikan ke nasabah</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-8 bg-gray-50 flex justify-between items-center border-t">
            <p class="text-[10px] font-bold text-gray-400 uppercase">Total Transaksi: 2</p>
            <div class="flex gap-6">
                <p class="text-[10px] font-bold text-green-600 uppercase">Total Masuk: 150</p>
                <p class="text-[10px] font-bold text-red-600 uppercase">Total Keluar: 30</p>
            </div>
        </div>
    </div>
</div>
@endsection