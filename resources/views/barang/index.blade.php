@extends('layouts.app')

@section('content')
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-[#00676F]">Data Barang</h1>
                <p class="text-sm text-gray-400">Kelola persediaan logistik operasional perbankan</p>
            </div>

            {{-- HANYA ADMIN yang bisa melihat tombol Tambah Barang --}}
            @can('manage-users')
                <button onclick="openModal('tambah')"
                    class="bg-[#00676F] text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-teal-800 transition whitespace-nowrap">
                    + Tambah Barang
                </button>
            @endcan
        </div>

        <div class="mb-6">
            <form action="{{ route('barang.index') }}" method="GET" class="flex gap-3 max-w-md">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama barang, jenis, atau keterangan..."
                        class="w-full bg-white border border-gray-200 rounded-2xl pl-12 pr-4 py-3.5 text-sm focus:ring-2 focus:ring-[#00676F] focus:border-none font-medium shadow-sm text-gray-700">
                </div>
                <button type="submit"
                    class="bg-[#00676F] text-white px-5 rounded-2xl font-bold text-sm shadow-md hover:bg-teal-800 transition">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('barang.index') }}"
                        class="bg-gray-100 text-gray-500 px-4 rounded-2xl font-bold text-sm flex items-center justify-center hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-2xl font-bold">
                {{ session('success') }}
            </div>
        @endif

        {{-- BANNER INDIKATOR FILTER STOK MENIPIS (BARU) --}}
        @if (request('filter') === 'menipis')
            <div
                class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl font-bold flex justify-between items-center text-xs tracking-wide uppercase">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-filter text-amber-600 animate-bounce"></i>
                    <span>Menampilkan Mode Kritis: Hanya Item dengan Stok &le; 5</span>
                </div>
                <a href="{{ route('barang.index') }}"
                    class="bg-amber-600 text-white px-4 py-2 rounded-xl text-[10px] font-black tracking-widest shadow-md hover:bg-amber-700 transition">
                    Tampilkan Semua Barang
                </a>
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
                    @forelse ($barangs as $index => $b)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-6 text-center font-bold text-[#00676F]">
                                {{ $barangs->firstItem() + $index }}
                            </td>
                            <td class="p-6">
                                <p class="font-bold text-gray-800">{{ $b->nama_barang }}</p>

                                {{-- Pemetaan Warna Badge Tiap Jenis --}}
                                @php
                                    $badgeStyle = 'bg-cyan-50 text-cyan-700 border-cyan-200';

                                    switch ($b->jenis) {
                                        case 'Buku Tabungan':
                                        case 'Buku':
                                            $badgeStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                            break;
                                        case 'Kartu ATM':
                                        case 'ATM':
                                            $badgeStyle = 'bg-teal-50 text-teal-700 border-teal-200';
                                            break;
                                        case 'Token PIN':
                                            $badgeStyle = 'bg-violet-50 text-violet-700 border-violet-200';
                                            break;
                                        case 'Slip Transaksi':
                                            $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                                            break;
                                        case 'Formulir':
                                            $badgeStyle = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                            break;
                                        case 'Logistik ATK':
                                            $badgeStyle = 'bg-slate-100 text-slate-700 border-slate-300';
                                            break;
                                    }
                                @endphp

                                <span
                                    class="inline-flex items-center text-[9px] px-2.5 py-0.5 rounded-full font-black border uppercase tracking-wider mt-1 {{ $badgeStyle }}">
                                    <i class="fa-solid fa-circle text-[5px] mr-1.5 opacity-70"></i>{{ $b->jenis ?? 'Umum' }}
                                </span>
                            </td>

                            {{-- Visual Peringatan Stok Menipis <= 5 --}}
                            <td class="p-6 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span
                                        class="font-black text-lg {{ $b->stok <= 5 ? 'text-amber-600' : 'text-gray-800' }}">
                                        {{ $b->stok }}
                                    </span>
                                    @if ($b->stok <= 5)
                                        <span
                                            class="inline-flex items-center text-[8px] font-black px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200/70 uppercase tracking-widest mt-1 animate-pulse">
                                            <i class="fa-solid fa-triangle-exclamation mr-1 text-[7px]"></i>Menipis
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="p-6 text-sm text-gray-400 font-bold uppercase">{{ $b->satuan }}</td>

                            {{-- Proteksi Baris Aksi untuk Admin --}}
                            @can('manage-users')
                                <td class="p-6">
                                    <div class="flex justify-center gap-3">
                                        <button onclick="openModal('edit', {{ json_encode($b) }})"
                                            class="bg-orange-100 text-orange-600 p-3 rounded-2xl hover:bg-orange-500 hover:text-white transition shadow-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('barang.destroy', $b->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-100 text-red-600 p-3 rounded-2xl hover:bg-red-500 hover:text-white transition shadow-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="p-12 text-center text-sm font-bold text-gray-400 uppercase tracking-widest">
                                <i class="fa-solid fa-box-open block text-3xl mb-3 text-gray-300"></i> Data Barang Tidak
                                Ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-6 bg-gray-50/50 border-t border-gray-100 branding-pagination">
                {{ $barangs->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH/EDIT BARANG --}}
    <div id="modalBSI" class="fixed inset-0 bg-teal-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[45px] shadow-2xl overflow-hidden border-b-[10px] border-[#F2A900]">
            <div class="p-10">
                <h3 id="mTitle" class="text-2xl font-black text-[#00676F] italic mb-8 text-center">Tambah Barang Baru
                </h3>
                <form id="mForm" method="POST" class="space-y-5">
                    @csrf
                    <div id="mMethod"></div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Jenis
                            Barang</label>
                        <select name="jenis" id="mJenis" required
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1 cursor-pointer">
                            <option value="" disabled selected hidden>-- Pilih Jenis Barang --</option>
                            <option value="Buku Tabungan">Buku Tabungan</option>
                            <option value="Kartu ATM">Kartu ATM / Debit</option>
                            <option value="Token PIN">Token PIN Aktivasi</option>
                            <option value="Slip Transaksi">Slip Transaksi (Setoran/Penarikan)</option>
                            <option value="Formulir">Formulir Layanan Nasabah</option>
                            <option value="Logistik ATK">Logistik Operasional & ATK</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Nama Item /
                            Spesifikasi</label>
                        <input type="text" name="nama_barang" id="mNama"
                            placeholder="Contoh: Buku Tabungan Easy Wadiah / Kartu GPN Chip"
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1"
                            required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Jumlah
                                Stok</label>
                            <input type="number" name="stok" id="mStok" placeholder="Jumlah Stok"
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] mt-1"
                                required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Satuan</label>
                            <select name="satuan" id="mSatuan"
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1 cursor-pointer">
                                <option value="Pcs">Pcs</option>
                                <option value="Box">Box</option>
                                <option value="Buku">Buku</option>
                                <option value="Lembar">Lembar</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Keterangan
                            Tambahan</label>
                        <textarea name="keterangan" id="mKet" placeholder="Keterangan instansi, lokasi rak, atau catatan lainnya..."
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] h-28 mt-1"></textarea>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal()"
                            class="flex-1 py-4 font-black text-gray-400 uppercase tracking-widest text-xs transition hover:text-red-500">Batal</button>
                        <button type="submit"
                            class="flex-[2] bg-[#00676F] text-white py-4 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-teal-900/20 transition hover:bg-[#004d54]">Simpan
                            Ke Gudang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalBSI');
        const form = document.getElementById('mForm');

        function openModal(mode, data = null) {
            @if (auth()->user()->jabatan !== 'admin')
                alert('Akses Ditolak: Anda bukan Admin!');
                return;
            @endif

            modal.classList.replace('hidden', 'flex');
            if (mode === 'edit') {
                document.getElementById('mTitle').innerText = 'Edit Data Barang';
                form.action = "/barang/update/" + data.id;
                document.getElementById('mMethod').innerHTML = '@method('PUT')';

                let jenisBarang = data.jenis || '';
                if (jenisBarang === 'Buku') jenisBarang = 'Buku Tabungan';
                if (jenisBarang === 'ATM') jenisBarang = 'Kartu ATM';

                document.getElementById('mJenis').value = jenisBarang;
                document.getElementById('mNama').value = data.nama_barang;
                document.getElementById('mStok').value = data.stok;
                document.getElementById('mSatuan').value = data.satuan;
                document.getElementById('mKet').value = data.keterangan;
            } else {
                document.getElementById('mTitle').innerText = 'Tambah Barang Baru';
                form.action = "{{ route('barang.store') }}";
                document.getElementById('mMethod').innerHTML = '';
                form.reset();
                document.getElementById('mJenis').value = '';
            }
        }

        function closeModal() {
            modal.classList.replace('flex', 'hidden');
        }
    </script>
@endsection
