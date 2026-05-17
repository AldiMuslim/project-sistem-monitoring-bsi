<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan sudah install laravel-dompdf

class TransaksiController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        $transaksi = Transaksi::with('barang')->orderBy('created_at', 'desc')->get();
        return view('transaksi.index', compact('barang', 'transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jenis_transaksi' => 'required|in:MASUK,KELUAR',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->jenis_transaksi == "MASUK") {
            $barang->stok += $request->jumlah;
        } else {
            if ($barang->stok < $request->jumlah) {
                return back()->with('error', 'Gagal! Stok ' . $barang->nama_barang . ' tidak mencukupi.');
            }
            $barang->stok -= $request->jumlah;
        }

        $barang->save();

        Transaksi::create([
            'barang_id'   => $request->barang_id,
            'nama_barang' => $barang->nama_barang,
            'jenis'       => $request->jenis_transaksi,
            'jumlah'      => $request->jumlah,
            'tanggal'     => $request->tanggal,
            'petugas'     => Auth::user()->name,
            'keterangan'  => $request->keterangan
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dicatat!');
    }

    /**
     * HALAMAN LAPORAN DENGAN FILTER
     */
    public function laporan(Request $request)
    {
        $query = Transaksi::query();

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter berdasarkan barang
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $riwayat = $query->orderBy('tanggal', 'desc')->get();
        $barangs = Barang::all();

        // Hitung total untuk ringkasan di bawah tabel
        $totalMasuk = $riwayat->where('jenis', 'MASUK')->sum('jumlah');
        $totalKeluar = $riwayat->where('jenis', 'KELUAR')->sum('jumlah');

        return view('laporan', compact('riwayat', 'barangs', 'totalMasuk', 'totalKeluar'));
    }

    /**
     * EKSPOR PDF PROFESIONAL
     */
    public function cetakPdf(Request $request)
    {
        $query = Transaksi::query();

        if ($request->filled('start_date')) $query->whereDate('tanggal', '>=', $request->start_date);
        if ($request->filled('end_date')) $query->whereDate('tanggal', '<=', $request->end_date);
        if ($request->filled('barang_id')) $query->where('barang_id', $request->barang_id);

        $riwayat = $query->orderBy('tanggal', 'asc')->get();

        // Data tambahan untuk header laporan
        $data = [
            'riwayat' => $riwayat,
            'tgl_cetak' => now()->translatedFormat('d F Y'),
            'admin' => Auth::user()->name
        ];

        $pdf = Pdf::loadView('laporan_pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Persediaan_BSI_' . now()->format('Ymd') . '.pdf');
    }
}
