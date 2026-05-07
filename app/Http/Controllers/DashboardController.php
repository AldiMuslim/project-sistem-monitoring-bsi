<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data untuk Kartu Ringkasan Atas (Nama variabel disesuaikan dengan view)
        $totalJenisBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $transaksiHariIni = Transaksi::whereDate('tanggal', Carbon::today())->count();
        $stokMenipisCount = Barang::where('stok', '<', 10)->count();
        $recentTransaksi = Transaksi::latest()->take(5)->get();

        // 2. Olah Data untuk Grafik Batang 7 Hari Terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            // Ambil tanggal mundur dari 6 hari lalu sampai hari ini
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            // Hitung total barang (jumlah) ATM dan Buku yang ditransaksikan hari itu
            $jumlahAtm = Transaksi::whereDate('tanggal', $date)
                ->where('nama_barang', 'LIKE', '%ATM%')
                ->sum('jumlah');

            $jumlahBuku = Transaksi::whereDate('tanggal', $date)
                ->where('nama_barang', 'LIKE', '%Buku%')
                ->sum('jumlah');

            // Konversi ke persentase untuk tinggi grafik (Misal 50 transaksi = 100% tinggi)
            // Anda bisa mengubah angka 50 ini jika transaksinya lebih besar
            $tinggiAtm = min(($jumlahAtm / 50) * 100, 100);
            $tinggiBuku = min(($jumlahBuku / 50) * 100, 100);

            // Beri tinggi minimal 2% agar batangnya tetap sedikit terlihat walau 0 transaksi
            if ($tinggiAtm == 0) $tinggiAtm = 2;
            if ($tinggiBuku == 0) $tinggiBuku = 2;

            $chartData[] = [
                'hari' => Carbon::now()->subDays($i)->translatedFormat('D'), // Contoh: Sen, Sel
                'atm' => $tinggiAtm,
                'buku' => $tinggiBuku,
                'asli_atm' => $jumlahAtm,
                'asli_buku' => $jumlahBuku
            ];
        }

        return view('dashboard', compact(
            'totalJenisBarang',
            'totalStok',
            'transaksiHariIni',
            'stokMenipisCount',
            'recentTransaksi',
            'chartData'
        ));
    }
}
