<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persediaan; // 1. Diubah dari App\Models\Barang
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * HALAMAN UTAMA TRANSAKSI
     * Mendukung Fitur Pencarian, Filter Kategori Mutasi, & Paginasi
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenisFilter = $request->input('jenis_filter');

        // Menggunakan paginate(10) agar fungsi firstItem() dan links() di Blade bekerja sempurna
        $transaksi = DB::table('transaksis')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_barang', 'LIKE', "%{$search}%")
                        ->orWhere('petugas', 'LIKE', "%{$search}%");
                });
            })
            ->when($jenisFilter, function ($query, $jenisFilter) {
                return $query->where('jenis', $jenisFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // 2. Mengambil data master persediaan untuk select option dropdown di form atas
        $persediaan = Persediaan::orderBy('nama_persediaan', 'asc')->get();

        return view('transaksi.index', compact('transaksi', 'persediaan'));
    }

    /**
     * SIMPAN TRANSAKSI MUTASI BARU & UPDATE STOK GUDANG
     * Mendukung validasi input dropdown 'persediaan_id' & 'jenis_transaksi'
     */
    public function store(Request $request)
    {
        $request->validate([
            'persediaan_id'   => 'required|exists:persediaan,id', // Diubah dari barang_id & barangs
            'jenis_transaksi' => 'required|in:MASUK,KELUAR',
            'jumlah'          => 'required|numeric|min:1',
            'tanggal'         => 'required|date',
        ]);

        $persediaanObj = Persediaan::find($request->persediaan_id); // Diubah dari Barang

        if ($persediaanObj) {
            // Kalkulasi perubahan stok fisik di gudang
            if ($request->jenis_transaksi == 'MASUK') {
                $persediaanObj->stok += $request->jumlah;
            } else {
                if ($persediaanObj->stok < $request->jumlah) {
                    return back()->with('error', 'Stok di gudang tidak mencukupi untuk melakukan transaksi KELUAR ini!');
                }
                $persediaanObj->stok -= $request->jumlah;
            }

            $persediaanObj->save();

            // Catat log ke tabel transaksis
            DB::table('transaksis')->insert([
                'nama_barang' => $persediaanObj->nama_persediaan, // Kolom log tetap nama_barang, diisi dari nama_persediaan
                'jenis'       => $request->jenis_transaksi,
                'jumlah'      => $request->jumlah,
                'petugas'     => auth()->user()->name,
                'tanggal'     => $request->tanggal,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return back()->with('success', 'Transaksi mutasi gudang berhasil dibukukan!');
        }

        return back()->with('error', 'Terjadi kesalahan: Data persediaan tidak valid!');
    }
}
