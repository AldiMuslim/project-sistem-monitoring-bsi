@extends('layouts.app')
@section('page_title', 'Transaksi')
@section('content')
<div class="space-y-8">
    <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Input Transaksi Baru</h3>
        <form class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nama Barang</label>
                <select class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm focus:ring-2 focus:ring-[#00676F]">
                    <option>Kartu ATM BSI</option>
                    <option>Buku Tabungan BSI</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Jenis</label>
                <select class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold">
                    <option class="text-green-600">MASUK</option>
                    <option class="text-red-600">KELUAR</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Jumlah</label>
                <input type="number" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm" placeholder="Contoh: 50">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Tanggal</label>
                <input type="date" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm text-gray-500">
            </div>
            <button class="bg-[#00676F] text-white py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-teal-50 hover:bg-teal-800 transition">
                Simpan Transaksi
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest">Data Transaksi Terkini</h3>
        </div>
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
                    <td class="py-5 px-8 font-black text-[#00676F]">01</td>
                    <td class="py-5 text-sm text-gray-500">24/04/2026</td>
                    <td class="py-5 font-bold text-gray-700">Kartu ATM BSI</td>
                    <td class="py-5">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase">Masuk</span>
                    </td>
                    <td class="py-5 font-black text-gray-800">50</td>
                    <td class="py-5 px-8 text-xs text-gray-400 italic font-medium">Stok masuk awal</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection