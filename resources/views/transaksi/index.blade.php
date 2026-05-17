@extends('layouts.app')
@section('page_title', 'Transaksi')

@section('content')
    <div class="space-y-8 p-8">
        {{-- BAGIAN NOTIFIKASI DIHAPUS DARI SINI (Pindahkan ke layouts/app.blade.php agar tidak double) --}}

        {{-- Bagian Atas: Input Transaksi Baru --}}
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Input Transaksi Baru</h3>

            <form action="{{ route('transaksi.store') }}" method="POST"
                class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nama Barang</label>
                    <select name="barang_id"
                        class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm focus:ring-2 focus:ring-[#00676F] font-bold"
                        required>
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok: {{ $b->stok }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Jenis</label>
                    <select name="jenis_transaksi" class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm font-bold"
                        required>
                        <option value="MASUK" class="text-green-600">MASUK (+)</option>
                        <option value="KELUAR" class="text-red-600">KELUAR (-)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Jumlah</label>
                    <input type="number" name="jumlah" min="1"
                        class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm" placeholder="50" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border-none rounded-2xl p-3 text-sm text-gray-500" required>
                </div>

                <button type="submit"
                    class="bg-[#00676F] text-white py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-teal-50 hover:bg-teal-800 transition">
                    Simpan Transaksi
                </button>
            </form>
        </div>

        {{-- Bagian Bawah: Data Transaksi Terkini --}}
        <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest">Data Transaksi Terkini</h3>
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
                            <th class="py-5">Petugas</th>
                            <th class="py-5 px-8">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transaksi as $index => $t)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-5 px-8 font-black text-[#00676F]">{{ $index + 1 }}</td>
                                <td class="py-5 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                                <td class="py-5 font-bold text-gray-700">{{ $t->nama_barang }}</td>
                                <td class="py-5">
                                    {{-- PERBAIKAN: Menggunakan kolom 'jenis' sesuai database --}}
                                    @if ($t->jenis == 'MASUK')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase">Masuk</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[9px] font-black uppercase">Keluar</span>
                                    @endif
                                </td>
                                <td class="py-5 font-black text-gray-800">{{ $t->jumlah }}</td>
                                <td class="py-5 text-xs font-bold text-gray-600">{{ $t->petugas }}</td>
                                <td class="py-5 px-8 text-xs text-gray-400 italic font-medium">
                                    {{ $t->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-400 font-bold uppercase text-xs tracking-widest">
                                    Belum ada data transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection