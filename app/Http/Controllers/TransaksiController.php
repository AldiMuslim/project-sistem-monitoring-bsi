<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
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

        // Mengambil data master barang untuk select option dropdown di form atas
        $barang = Barang::orderBy('nama_barang', 'asc')->get();

        return view('transaksi.index', compact('transaksi', 'barang'));
    }

    /**
     * SIMPAN TRANSAKSI MUTASI BARU & UPDATE STOK GUDANG
     * Mendukung validasi input dropdown 'barang_id' & 'jenis_transaksi'
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jenis_transaksi' => 'required|in:MASUK,KELUAR',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        $barangObj = Barang::find($request->barang_id);

        if ($barangObj) {
            // Kalkulasi perubahan stok fisik di gudang
            if ($request->jenis_transaksi == 'MASUK') {
                $barangObj->stok += $request->jumlah;
            } else {
                if ($barangObj->stok < $request->jumlah) {
                    return back()->with('error', 'Stok di gudang tidak mencukupi untuk melakukan transaksi KELUAR ini!');
                }
                $barangObj->stok -= $request->jumlah;
            }

            $barangObj->save();

            // Catat log ke tabel transaksis
            DB::table('transaksis')->insert([
                'nama_barang' => $barangObj->nama_barang,
                'jenis'       => $request->jenis_transaksi,
                'jumlah'      => $request->jumlah,
                'petugas'     => auth()->user()->name,
                'tanggal'     => $request->tanggal,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return back()->with('success', 'Transaksi mutasi gudang berhasil dibukukan!');
        }

        return back()->with('error', 'Terjadi kesalahan: Data barang tidak valid!');
    }
}
