@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-[#00676F]">Data Barang</h1>
            <p class="text-sm text-gray-400">Kelola persediaan Kartu ATM & Buku Tabungan</p>
        </div>
        
        {{-- HANYA ADMIN yang bisa melihat tombol Tambah Barang --}}
        @can('manage-users')
        <button onclick="openModal('tambah')" class="bg-[#00676F] text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-teal-800 transition">
            + Tambah Barang
        </button>
        @endcan
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-2xl font-bold">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-[35px] shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50 border-b">
                <tr class="text-[11px] uppercase tracking-widest text-gray-400">
                    <th class="p-6 text-center">No</th>
                    <th class="p-6">Nama Barang</th>
                    <th class="p-6 text-center">Stok</th>
                    <th class="p-6">Satuan</th>
                    {{-- Kolom Aksi hanya tampil untuk Admin --}}
                    @can('manage-users')
                    <th class="p-6 text-center">Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($barangs as $index => $b)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-6 text-center font-bold text-[#00676F]">{{ $index + 1 }}</td>
                    <td class="p-6">
                        <p class="font-bold text-gray-800">{{ $b->nama_barang }}</p>
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $b->jenis == 'ATM' ? 'bg-teal-100 text-teal-600' : 'bg-blue-100 text-blue-600' }} font-black">
                            {{ $b->jenis }}
                        </span>
                    </td>
                    <td class="p-6 text-center font-black text-lg">{{ $b->stok }}</td>
                    <td class="p-6 text-sm text-gray-400 font-bold uppercase">{{ $b->satuan }}</td>
                    
                    {{-- Proteksi Baris Aksi untuk Admin --}}
                    @can('manage-users')
                    <td class="p-6">
                        <div class="flex justify-center gap-3">
                            <button onclick="openModal('edit', {{ json_encode($b) }})" class="bg-orange-100 text-orange-600 p-3 rounded-2xl hover:bg-orange-500 hover:text-white transition shadow-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="{{ route('barang.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-600 p-3 rounded-2xl hover:bg-red-500 hover:text-white transition shadow-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endcan
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal hanya perlu dirender jika Admin, tapi agar JS tidak error tetap dibiarkan dengan proteksi --}}
<div id="modalBSI" class="fixed inset-0 bg-teal-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-[45px] shadow-2xl overflow-hidden border-b-[10px] border-[#F2A900]">
        <div class="p-10">
            <h3 id="mTitle" class="text-2xl font-black text-[#00676F] italic mb-8 text-center">Tambah Barang Baru</h3>
            <form id="mForm" method="POST" class="space-y-5">
                @csrf 
                <div id="mMethod"></div>
                
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Pilih Item</label>
                    <select name="nama_barang" id="mNama" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F]">
                        <option value="Kartu ATM Visa Gold">Kartu ATM Visa Gold</option>
                        <option value="Kartu ATM GPN Chip">Kartu ATM GPN Chip</option>
                        <option value="Buku Tabungan Easy Wadiah">Buku Tabungan Easy Wadiah</option>
                        <option value="Buku Tabungan Bisnis">Buku Tabungan Bisnis</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="stok" id="mStok" placeholder="Jumlah Stok" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F]" required>
                    <select name="satuan" id="mSatuan" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F]">
                        <option value="Pcs">Pcs</option>
                        <option value="Box">Box</option>
                    </select>
                </div>

                <textarea name="keterangan" id="mKet" placeholder="Keterangan..." class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] h-28"></textarea>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 py-4 font-black text-gray-400 uppercase tracking-widest text-xs">Batal</button>
                    <button type="submit" class="flex-[2] bg-[#00676F] text-white py-4 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-teal-900/20">Simpan Ke Gudang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalBSI');
    const form = document.getElementById('mForm');
    
    function openModal(mode, data = null) {
        // Tambahan proteksi sisi client: Jika bukan admin, jangan jalankan modal
        @if(auth()->user()->jabatan !== 'admin')
            alert('Akses Ditolak: Anda bukan Admin!');
            return;
        @endif

        modal.classList.replace('hidden', 'flex');
        if(mode === 'edit') {
            document.getElementById('mTitle').innerText = 'Edit Data Barang';
            form.action = "/barang/update/" + data.id; 
            document.getElementById('mMethod').innerHTML = '@method("PUT")';
            document.getElementById('mNama').value = data.nama_barang;
            document.getElementById('mStok').value = data.stok;
            document.getElementById('mSatuan').value = data.satuan;
            document.getElementById('mKet').value = data.keterangan;
        } else {
            document.getElementById('mTitle').innerText = 'Tambah Barang Baru';
            form.action = "{{ route('barang.store') }}";
            document.getElementById('mMethod').innerHTML = '';
            form.reset();
        }
    }

    function closeModal() { 
        modal.classList.replace('flex', 'hidden'); 
    }
</script>
@endsection