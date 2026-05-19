@extends('layouts.app')
@section('page_title', 'Kelola Pengguna')

@section('content')
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-[#00676F]">Kelola Pengguna</h1>
                <p class="text-sm text-gray-400">Manajemen hak akses akun Admin & Petugas BSI</p>
            </div>

            <button onclick="openUserModal('tambah')"
                class="bg-[#00676F] text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-teal-800 transition">
                + Tambah Pengguna
            </button>
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
                        <th class="p-6">Nama Lengkap</th>
                        <th class="p-6">Alamat Email</th>
                        <th class="p-6 text-center">Hak Akses / Jabatan</th>
                        <th class="p-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($users as $index => $u)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-6 text-center font-bold text-[#00676F]">{{ $index + 1 }}</td>
                            <td class="p-6 font-bold text-gray-800">{{ $u->name }}</td>
                            <td class="p-6 text-sm text-gray-500 font-medium">{{ $u->email }}</td>
                            <td class="p-6 text-center">
                                @if (strtolower($u->jabatan) === 'admin')
                                    <span
                                        class="inline-flex items-center text-[9px] px-3 py-0.5 rounded-full font-black border bg-teal-50 text-teal-700 border-teal-200 uppercase tracking-wider">
                                        <i class="fa-solid fa-shield text-[7px] mr-1"></i> Administrator
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center text-[9px] px-3 py-0.5 rounded-full font-black border bg-blue-50 text-blue-700 border-blue-200 uppercase tracking-wider">
                                        <i class="fa-solid fa-user text-[7px] mr-1"></i> Petugas / Anggota
                                    </span>
                                @endif
                            </td>
                            <td class="p-6">
                                <div class="flex justify-center gap-3">
                                    <button onclick="openUserModal('edit', {{ json_encode($u) }})"
                                        class="bg-orange-100 text-orange-600 p-3 rounded-2xl hover:bg-orange-500 hover:text-white transition shadow-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="/users/delete/{{ $u->id }}" method="POST"
                                        onsubmit="return confirm('Hapus pengguna {{ $u->name }} dari sistem?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-100 text-red-600 p-3 rounded-2xl hover:bg-red-500 hover:text-white transition shadow-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH / EDIT USER PREMIUM --}}
    <div id="modalUserBSI"
        class="fixed inset-0 bg-teal-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[45px] shadow-2xl overflow-hidden border-b-[10px] border-[#F2A900]">
            <div class="p-10">
                <h3 id="uModalTitle" class="text-2xl font-black text-[#00676F] italic mb-8 text-center">Registrasi Pengguna
                    Baru</h3>
                <form id="uForm" method="POST" class="space-y-5">
                    @csrf
                    <div id="uMethod"></div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Nama
                            Lengkap</label>
                        <input type="text" name="name" id="uNama" placeholder="Nama Lengkap Karyawan"
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1"
                            required>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Alamat Email
                            Resmi</label>
                        <input type="email" name="email" id="uEmail" placeholder="contoh@bsi.co.id"
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1"
                            required>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Hak Akses
                            Sistem</label>
                        <select name="jabatan" id="uJabatan" required
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1 cursor-pointer">
                            <option value="" disabled selected hidden>-- Pilih Hak Akses --</option>
                            <option value="admin">Admin (Full Kontrol)</option>
                            <option value="petugas">Petugas / Anggota (Hanya Transaksi)</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 tracking-widest">Kata Sandi
                            Akun</label>
                        <input type="password" name="password" id="uPassword" placeholder="Min. 8 Karakter"
                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-[#00676F] font-bold text-[#00676F] mt-1">
                        <p id="uPasswordHelp" class="text-[9px] text-gray-400 mt-1 ml-2 hidden">*Kosongkan jika tidak ingin
                            mengganti password</p>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeUserModal()"
                            class="flex-1 py-4 font-black text-gray-400 uppercase tracking-widest text-xs transition hover:text-red-500">Batal</button>
                        <button type="submit"
                            class="flex-[2] bg-[#00676F] text-white py-4 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-teal-900/20 transition hover:bg-[#004d54]">Simpan
                            Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const uModal = document.getElementById('modalUserBSI');
        const userForm = document.getElementById('uForm');

        function openUserModal(mode, data = null) {
            uModal.classList.replace('hidden', 'flex');

            if (mode === 'edit') {
                document.getElementById('uModalTitle').innerText = 'Ubah Informasi Pengguna';
                userForm.action = "/users/update/" + data.id;
                document.getElementById('uMethod').innerHTML = '@method('PUT')';

                document.getElementById('uNama').value = data.name;
                document.getElementById('uEmail').value = data.email;
                document.getElementById('uJabatan').value = data.jabatan;

                // Set password opsional saat edit data dilakukan
                document.getElementById('uPassword').required = false;
                document.getElementById('uPassword').placeholder = "•••••••• (Isi jika diganti)";
                document.getElementById('uPasswordHelp').classList.remove('hidden');
            } else {
                document.getElementById('uModalTitle').innerText = 'Registrasi Pengguna Baru';
                userForm.action = "{{ route('users.store') }}";
                document.getElementById('uMethod').innerHTML = '';
                userForm.reset();

                document.getElementById('uPassword').required = true;
                document.getElementById('uPassword').placeholder = "Masukkan Password Akun";
                document.getElementById('uPasswordHelp').addClass('hidden');
            }
        }

        function closeModalUser() {
            uModal.classList.replace('flex', 'hidden');
        }

        // Alias fungsi agar penulisan onclick di tombol batal bekerja normal
        function closeUserModal() {
            uModal.classList.replace('flex', 'hidden');
        }
    </script>
@endsection
