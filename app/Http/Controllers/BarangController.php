<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate; // Tambahkan ini
use Carbon\Carbon;

class BarangController extends Controller
{
    /**
     * DASHBOARD DINAMIS
     * Dapat diakses oleh Admin dan Petugas
     */
    public function dashboard()
    {
        $totalJenisBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())->count();
        $stokMenipisCount = Barang::where('stok', '<=', 5)->count();

        $recentTransaksi = Transaksi::orderBy('created_at', 'desc')->limit(5)->get();

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            $jumlahAtm = Transaksi::whereDate('created_at', $date)
                ->where('nama_barang', 'LIKE', '%ATM%')
                ->sum('jumlah');

            $jumlahBuku = Transaksi::whereDate('created_at', $date)
                ->where('nama_barang', 'LIKE', '%BUKU%')
                ->sum('jumlah');

            $tinggiAtm = min(($jumlahAtm / 50) * 100, 100);
            $tinggiBuku = min(($jumlahBuku / 50) * 100, 100);

            $chartData[] = [
                'hari' => Carbon::now()->subDays($i)->translatedFormat('D'),
                'atm' => $tinggiAtm > 0 ? $tinggiAtm : 2,
                'buku' => $tinggiBuku > 0 ? $tinggiBuku : 2,
                'asli_atm' => (int) $jumlahAtm,
                'asli_buku' => (int) $jumlahBuku
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

    /**
     * HALAMAN DATA BARANG
     * Dapat diakses oleh Admin dan Petugas (Read-Only untuk Petugas)
     */
    public function index()
    {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    /**
     * SIMPAN BARANG BARU
     * KHUSUS ADMIN
     */
    public function store(Request $request)
    {
        // Proteksi Gate: Hanya Admin
        Gate::authorize('manage-users');

        $request->validate([
            'nama_barang' => 'required',
            'stok' => 'required|numeric',
            'satuan' => 'required',
        ]);

        $jenis = str_contains($request->nama_barang, 'Kartu') ? 'ATM' : 'Buku';

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'jenis'       => $jenis,
            'stok'        => $request->stok,
            'satuan'      => $request->satuan,
            'keterangan'  => $request->keterangan,
        ]);

        return back()->with('success', 'Barang baru berhasil disimpan!');
    }

    /**
     * UPDATE DATA BARANG
     * KHUSUS ADMIN
     */
    public function update(Request $request, $id)
    {
        // Proteksi Gate: Hanya Admin
        Gate::authorize('manage-users');

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());
        return back()->with('success', 'Data berhasil diubah!');
    }

    /**
     * HAPUS BARANG
     * KHUSUS ADMIN
     */
    public function destroy($id)
    {
        // Proteksi Gate: Hanya Admin
        Gate::authorize('manage-users');

        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * HALAMAN TRANSAKSI
     * Dapat diakses oleh Admin dan Petugas
     */
    public function transaksi()
    {
        $riwayat = DB::table('transaksis')->orderBy('created_at', 'desc')->get();
        return view('barang.barang_keluar', compact('riwayat'));
    }

    /**
     * SIMPAN TRANSAKSI & UPDATE STOK
     * Admin dan Petugas bisa melakukan transaksi
     */
    public function storeTransaksi(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $barang = Barang::where('nama_barang', $request->nama_barang)->first();

        if ($barang) {
            if ($request->jenis == 'MASUK') {
                $barang->stok += $request->jumlah;
            } else {
                if ($barang->stok < $request->jumlah) {
                    return back()->with('error', 'Stok tidak cukup!');
                }
                $barang->stok -= $request->jumlah;
            }

            $barang->save();

            DB::table('transaksis')->insert([
                'nama_barang' => $request->nama_barang,
                'jenis'       => $request->jenis,
                'jumlah'      => $request->jumlah,
                'petugas'     => auth()->user()->name,
                'tanggal'     => $request->tanggal ?? now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return back()->with('success', 'Transaksi berhasil disimpan!');
        }
        return back()->with('error', 'Barang tidak ditemukan!');
    }

    /**
     * HALAMAN LAPORAN
     */
    
}
