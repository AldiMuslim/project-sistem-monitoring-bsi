@extends('layouts.app')

@section('content')
<div class="p-8 flex justify-center">
    <div class="bg-white w-full max-w-lg rounded-[45px] shadow-2xl overflow-hidden border-b-[10px] border-[#F2A900]">
        <div class="p-10">
            <h3 class="text-2xl font-black text-[#00676F] italic mb-8 text-center">Tambah Barang Baru</h3>
            
            <form action="{{ route('barang.store') }}" method="POST" class="space-y-5">
                @csrf 
                
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Nama Barang</label>
                    <select name="nama_barang" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F]">
                        <option value="Kartu ATM Visa Gold">Kartu ATM Visa Gold</option>
                        <option value="Kartu ATM GPN Chip">Kartu ATM GPN Chip</option>
                        <option value="Buku Tabungan Easy Wadiah">Buku Tabungan Easy Wadiah</option>
                        <option value="Buku Tabungan Bisnis">Buku Tabungan Bisnis</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Stok</label>
                        <input type="number" name="stok" placeholder="0" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F]" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Satuan</label>
                        <select name="satuan" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F]">
                            <option value="Pcs">Pcs</option>
                            <option value="Box">Box</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Keterangan</label>
                    <textarea name="keterangan" placeholder="Tambahkan catatan jika ada..." class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] h-28"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('barang.index') }}" class="flex-1 py-4 font-black text-center text-gray-400 uppercase tracking-widest text-xs">Batal</a>
                    <button type="submit" class="flex-[2] bg-[#00676F] text-white py-4 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-teal-900/20">Simpan Ke Gudang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection