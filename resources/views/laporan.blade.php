@extends('layouts.app')

@section('page_title', 'Laporan Inventaris')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-gray-800">Laporan Persediaan Asset</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Monitoring Kartu ATM & Buku Tabungan</p>
            </div>
            <button onclick="window.print()" class="bg-[#00676F] text-white px-6 py-3 rounded-2xl font-black text-xs hover:bg-[#004d53] transition flex items-center gap-2">
                <i class="fas fa-print"></i> CETAK PDF
            </button>
        </div>
        
        <div class="bg-[#F2A900] p-8 rounded-[32px] shadow-lg text-gray-900 flex flex-col justify-center">
            <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total Stok Tersedia</p>
            <h2 class="text-3xl font-black">{{ $total_stok ?? 0 }} <span class="text-sm font-bold">Unit</span></h2>
        </div>
    </div>

    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Barang</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Stok Fisik</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($barang as $item)
                    <tr class="hover:bg-gray-50/50 transition duration-200">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-[#00676F]">
                                    <i class="fas {{ str_contains($item->nama_barang, 'Kartu') ? 'fa-credit-card' : 'fa-book' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800">{{ $item->nama_barang }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $item->jenis ?? 'Asset BSI' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-sm font-black {{ $item->stok < 0 ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $item->stok }} <span class="text-[10px] text-gray-400 font-bold">Unit</span>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($item->stok > 10)
                                <span class="px-4 py-1.5 bg-green-50 text-green-600 text-[10px] font-black rounded-full border border-green-100">AMAN</span>
                            @elseif($item->stok >= 0)
                                <span class="px-4 py-1.5 bg-orange-50 text-orange-600 text-[10px] font-black rounded-full border border-orange-100">MENIPIS</span>
                            @else
                                <span class="px-4 py-1.5 bg-red-50 text-red-600 text-[10px] font-black rounded-full border border-red-100">MINUS</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-12 text-center text-gray-400 font-bold italic">Belum ada data barang tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection