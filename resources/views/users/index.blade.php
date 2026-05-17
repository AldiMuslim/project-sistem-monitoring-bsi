@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Daftar Pengguna / Petugas</h2>

            {{-- Hanya Admin yang bisa melihat tombol Tambah Pengguna --}}
            @if (auth()->user()->jabatan === 'admin')
                <a href="{{ route('pengguna.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Tambah Pengguna
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nama</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-center">Jabatan</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($users as $user)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                <span class="font-medium">{{ $user->name }}</span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                {{ $user->email }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span
                                    class="bg-{{ $user->jabatan === 'admin' ? 'purple' : 'green' }}-200 text-{{ $user->jabatan === 'admin' ? 'purple' : 'green' }}-600 py-1 px-3 rounded-full text-xs">
                                    {{ ucfirst($user->jabatan) }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    {{-- Proteksi Tampilan: Hanya Admin yang bisa Edit/Hapus --}}
                                    @if (auth()->user()->jabatan === 'admin')
                                        <a href="{{ route('pengguna.edit', $user->id) }}"
                                            class="w-4 mr-2 transform hover:text-yellow-500 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-4 ml-2 transform hover:text-red-500 hover:scale-110">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic text-xs">No Access</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
