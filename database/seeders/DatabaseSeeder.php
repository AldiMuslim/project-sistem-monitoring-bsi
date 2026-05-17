<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin (Gunakan updateOrCreate agar lebih aman)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin BSI',
                'jabatan' => 'admin', // Role Admin
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Buat Akun Petugas
        User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name' => 'Petugas BSI',
                'jabatan' => 'petugas', // Role Petugas
                'password' => bcrypt('password123'),
            ]
        );
        
        // 3. Masukkan Data Barang Dummy
        DB::table('barangs')->insert([
            [
                'id' => 1,
                'nama_barang' => 'KARTU ATM PLATINUM',
                'jenis' => 'Kartu',
                'stok' => 150,
                'satuan' => 'Pcs',
                'keterangan' => 'Kartu Platinum Nasabah Prioritas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama_barang' => 'BUKU TABUNGAN WADIAH',
                'jenis' => 'Buku',
                'stok' => 200,
                'satuan' => 'Pcs',
                'keterangan' => 'Buku Tabungan Reguler',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_barang' => 'KARTU ATM GOLD',
                'jenis' => 'Kartu',
                'stok' => 3,
                'satuan' => 'Pcs',
                'keterangan' => 'Kartu Gold',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 4. Masukkan Data Transaksi 7 Hari Terakhir
        $transaksis = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $transaksis[] = [
                'nama_barang' => 'KARTU ATM PLATINUM', 
                'jenis' => 'KELUAR', 
                'jumlah' => rand(10, 45), 
                'tanggal' => $date->format('Y-m-d'),
                'petugas' => 'Admin BSI', // Nama petugas disesuaikan
                'created_at' => $date,
                'updated_at' => $date,
            ];

            $transaksis[] = [
                'nama_barang' => 'BUKU TABUNGAN WADIAH',
                'jenis' => 'KELUAR', 
                'jumlah' => rand(5, 30), 
                'tanggal' => $date->format('Y-m-d'),
                'petugas' => 'Petugas BSI', // Contoh transaksi oleh petugas
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        $transaksis[] = [
            'nama_barang' => 'KARTU ATM GOLD',
            'jenis' => 'MASUK', 
            'jumlah' => 100,
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'petugas' => 'Admin BSI',
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today(),
        ];

        DB::table('transaksis')->insert($transaksis);
    }
}