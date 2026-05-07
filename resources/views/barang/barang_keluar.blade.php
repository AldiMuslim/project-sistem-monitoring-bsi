@extends('layouts.app')

@section('content')
<div class="p-8 space-y-6">
    @if(session('success'))
        <div class="p-4 mb-4 bg-green-100 text-green-700 rounded-2xl font-bold border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 bg-red-100 text-red-700 rounded-2xl font-bold border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Input Transaksi Baru</h3>
        
        <form action="{{ route('transaksi.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nama Barang</label>
                <select name="nama_barang" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold text-[#00676F]">
                    <optgroup label="KARTU ATM">
                        <option value="Kartu ATM Visa Gold">Kartu ATM Visa Gold</option>
                        <option value="Kartu ATM GPN Chip">Kartu ATM GPN Chip</option>
                    </optgroup>
                    <optgroup label="BUKU TABUNGAN">
                        <option value="Buku Tabungan Easy Wadiah">Buku Tabungan Easy Wadiah</option>
                        <option value="Buku Tabungan Bisnis">Buku Tabungan Bisnis</option>
                    </optgroup>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Jenis</label>
                <select name="jenis" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-black">
                    <option value="MASUK" class="text-green-600">▲ MASUK</option>
                    <option value="KELUAR" class="text-red-600">▼ KELUAR</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Jumlah</label>
                <input type="number" name="jumlah" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm" placeholder="0" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm text-gray-500">
            </div>
            <button type="submit" class="bg-[#00676F] text-white py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-teal-800 transition">
                Simpan Transaksi
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50">
            <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest">Riwayat Transaksi Terkini</h3>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] text-gray-400 uppercase tracking-widest border-b">
                    <th class="p-6">Tanggal</th>
                    <th class="p-6">Nama Barang</th>
                    <th class="p-6 text-center">Jenis</th>
                    <th class="p-6 text-center">Jumlah</th>
                    <th class="p-6">Petugas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($riwayat as $t)
                <tr class="text-sm font-bold text-gray-600">
                    <td class="p-6 text-gray-400">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                    <td class="p-6 text-[#00676F]">{{ $t->nama_barang }}</td>
                    <td class="p-6 text-center">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black {{ $t->jenis == 'MASUK' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $t->jenis }}
                        </span>
                    </td>
                    <td class="p-6 text-center">{{ $t->jumlah }}</td>
                    <td class="p-6 text-xs text-gray-400">{{ $t->petugas }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center text-gray-400 italic">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection