@extends('layouts.app')
@section('page_title', 'Riwayat Transaksi')

@section('content')
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-[#00676F]">Logistik Masuk & Keluar</h1>
                <p class="text-sm text-gray-400">Pencatatan mutasi sirkulasi persediaan gudang</p>
            </div>

            <button onclick="openTransaksiModal()"
                class="bg-[#00676F] text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-teal-800 transition whitespace-nowrap">
                + Tambah Mutasi Barang
            </button>
        </div>

        <div class="mb-6 bg-white p-5 rounded-[25px] border border-gray-100 shadow-sm max-w-3xl">
            <form action="{{ route('transaksi.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">

                <div class="relative flex-[2]">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama barang atau petugas..."
                        class="w-full bg-gray-50 border-none rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-[#00676F] font-medium text-gray-700">
                </div>

                <div class="flex-1">
                    <select name="jenis_filter"
                        class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] cursor-pointer">
                        <option value="">Semua Mutasi</option>
                        <option value="MASUK" {{ request('jenis_filter') == 'MASUK' ? 'selected' : '' }}>Stok Masuk
                        </option>
                        <option value="KELUAR" {{ request('jenis_filter') == 'KELUAR' ? 'selected' : '' }}>Barang Keluar
                        </option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-[#00676F] text-white px-6 py-3 rounded-xl font-bold text-sm shadow-md hover:bg-teal-800 transition">
                        Filter
                    </button>
                    @if (request('search') || request('jenis_filter'))
                        <a href="{{ route('transaksi.index') }}"
                            class="bg-gray-100 text-gray-500 px-4 rounded-xl font-bold text-sm flex items-center justify-center hover:bg-gray-200 transition">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-2xl font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-2xl font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[35px] shadow-sm overflow-hidden border border-gray-100">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 border-b">
                    <tr class="text-[11px] uppercase tracking-widest text-gray-400">
                        <th class="p-6 text-center">No</th>
                        <th class="p-6">Tanggal / Waktu</th>
                        <th class="p-6">Nama Barang / Logistik</th>
                        <th class="p-6 text-center">Jenis Mutasi</th>
                        <th class="p-6 text-center">Volume</th>
                        <th class="p-6">Petugas Piket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($riwayat as $index => $r)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-6 text-center font-bold text-[#00676F]">
                                {{ $riwayat->firstItem() + $index }}
                            </td>
                            <td class="p-6 text-xs text-gray-400 font-bold">
                                {{ \Carbon\Carbon::parse($r->created_at)->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td class="p-6 font-bold text-gray-800">{{ $r->nama_barang }}</td>
                            <td class="p-6 text-center">
                                @if (strtoupper($r->jenis) === 'MASUK')
                                    <span
                                        class="inline-flex items-center text-[9px] px-3 py-0.5 rounded-full font-black border bg-emerald-50 text-emerald-700 border-emerald-200 uppercase tracking-wider">
                                        <i class="fa-solid fa-arrow-down-long mr-1 text-[8px]"></i> Stok Masuk
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center text-[9px] px-3 py-0.5 rounded-full font-black border bg-rose-50 text-rose-700 border-rose-200 uppercase tracking-wider">
                                        <i class="fa-solid fa-arrow-up-long mr-1 text-[8px]"></i> Barang Keluar
                                    </span>
                                @endif
                            </td>
                            <td
                                class="p-6 text-center font-black text-sm {{ strtoupper($r->jenis) == 'MASUK' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ strtoupper($r->jenis) == 'MASUK' ? '+' : '-' }}{{ number_format($r->jumlah) }}
                            </td>
                            <td class="p-6 text-sm text-gray-500 font-bold uppercase tracking-tighter">
                                <i class="fa-regular fa-user text-gray-400 mr-1 text-xs"></i>{{ $r->petugas ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="p-12 text-center text-sm font-bold text-gray-400 uppercase tracking-widest">
                                <i class="fa-solid fa-clock-rotate-left block text-3xl mb-3 text-gray-300"></i> Data mutasi
                                tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-6 bg-gray-50/50 border-t border-gray-100 text-gray-400">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL INPUT MUTASI TRANSAKSI BARU --}}
    <div id="modalTransaksiBSI"
        class="fixed inset-0 bg-teal-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[45px] shadow-2xl overflow-hidden border-b-[10px] border-[#F2A900]">
            <div class="p-10">
                <h3 class="text-2xl font-black text-[#00676F] italic mb-8 text-center">Form Mutasi Logistik</h3>

                <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Pilih Item
                            Logistik</label>
                        <select name="nama_barang" required
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1 cursor-pointer">
                            <option value="" disabled selected hidden>-- Klik Untuk Memilih Barang --</option>
                            @foreach ($barangs as $b)
                                <option value="{{ $b->nama_barang }}">
                                    {{ $b->nama_barang }} (Sisa Stok: {{ $b->stok }} {{ $b->satuan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Jenis
                            Pergerakan</label>
                        <select name="jenis" required
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1 cursor-pointer">
                            <option value="" disabled selected hidden>-- Pilih Alur Mutasi --</option>
                            <option value="MASUK">BARANG MASUK (Restock Pemasok/Pusat)</option>
                            <option value="KELUAR">BARANG KELUAR (Kebutuhan Operasional KCP)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Volume
                                Jumlah</label>
                            <input type="number" name="jumlah" min="1" placeholder="Banyak unit"
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold mt-1"
                                required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Tanggal
                                Buku</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold mt-1 text-gray-500"
                                required>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeTransaksiModal()"
                            class="flex-1 py-4 font-black text-gray-400 uppercase tracking-widest text-xs transition hover:text-red-500">Batal</button>
                        <button type="submit"
                            class="flex-[2] bg-[#00676F] text-white py-4 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-teal-900/20 transition hover:bg-[#004d54]">Eksekusi
                            Mutasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const tModal = document.getElementById('modalTransaksiBSI');

        function openTransaksiModal() {
            tModal.classList.replace('hidden', 'flex');
        }

        function closeTransaksiModal() {
            tModal.classList.replace('flex', 'hidden');
        }
    </script>
@endsection
