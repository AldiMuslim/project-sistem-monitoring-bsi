<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * HALAMAN LAPORAN MUTASI LOGISTIK
     * Menampilkan semua data berdasarkan filter tanggal untuk siap cetak
     */
    public function index(Request $request)
    {
        // Set default filter: dari tanggal 1 bulan ini sampai hari ini
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglSelesai = $request->input('tgl_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $jenis = $request->input('jenis');

        // Tarik data mutasi transaksi berdasarkan rentang periode
        // Laporan tidak menggunakan paginate() agar semua data keluar utuh saat dicetak
        $laporan = DB::table('transaksis')
            ->whereBetween('tanggal', [$tglMulai, $tglSelesai])
            ->when($jenis, function ($query, $jenis) {
                return $query->where('jenis', $jenis);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('laporan.index', compact('laporan', 'tglMulai', 'tglSelesai', 'jenis'));
    }
}
