@extends('layouts.app')

@section('content')
    <div class="p-8">
        <div class="max-w-lg bg-white rounded-[35px] shadow-sm p-10 border border-gray-100">
            <h2 class="text-2xl font-black text-[#00676F] mb-6">Tambah Transaksi</h2>

            {{-- Menampilkan Pesan Error jika stok kurang atau validasi gagal --}}
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-2xl font-bold text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('transaksi.store') }}" class="space-y-5">
                @csrf

                {{-- Pilihan Barang menggunakan ID agar terhubung ke tabel Barangs --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Nama Barang</label>
                    <select name="barang_id"
                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold"
                        required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok: {{ $b->stok }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilihan Jenis Transaksi --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Jenis Transaksi</label>
                    <select name="jenis_transaksi"
                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold"
                        required>
                        <option value="MASUK">BARANG MASUK (+)</option>
                        <option value="KELUAR">BARANG KELUAR (-)</option>
                    </select>
                </div>

                {{-- Input Jumlah --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Jumlah</label>
                    <input type="number" name="jumlah" min="1"
                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F]"
                        placeholder="0" required>
                </div>

                {{-- Input Tanggal --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F]"
                        required>
                </div>

                {{-- Input Keterangan --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan"
                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] h-24"
                        placeholder="Catatan transaksi..."></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('transaksi.index') }}"
                        class="flex-1 py-4 font-black text-gray-400 text-center uppercase tracking-widest text-xs">Batal</a>
                    <button type="submit"
                        class="flex-[2] bg-[#00676F] text-white py-4 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-teal-900/20 hover:bg-teal-800 transition">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
