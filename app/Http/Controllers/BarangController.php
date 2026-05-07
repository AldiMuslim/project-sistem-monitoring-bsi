<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{

    // 1. DASHBOARD DINAMIS (Sinkron ke tampilan Dashboard BSI)
    public function dashboard()
    {
        // 1. Data Ringkasan Atas
        $totalJenisBarang = \App\Models\Barang::count();
        $totalStok = \App\Models\Barang::sum('stok');
        $transaksiHariIni = \App\Models\Transaksi::whereDate('created_at', \Carbon\Carbon::today())->count();
        $stokMenipisCount = \App\Models\Barang::where('stok', '<=', 5)->count();

        // 2. Data Aktivitas Terbaru (Sisi Kanan)
        $recentTransaksi = \App\Models\Transaksi::orderBy('created_at', 'desc')->limit(5)->get();

        // 3. Olah Data Grafik 7 Hari Terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');

            // Hitung jumlah transaksi berdasar kata kunci (huruf besar/kecil tidak masalah)
            $jumlahAtm = \App\Models\Transaksi::whereDate('created_at', $date)
                ->where('nama_barang', 'LIKE', '%ATM%')
                ->sum('jumlah');

            $jumlahBuku = \App\Models\Transaksi::whereDate('created_at', $date)
                ->where('nama_barang', 'LIKE', '%BUKU%')
                ->sum('jumlah');

            // Konversi ke persentase tinggi (maksimal 100%)
            $tinggiAtm = min(($jumlahAtm / 50) * 100, 100);
            $tinggiBuku = min(($jumlahBuku / 50) * 100, 100);

            $chartData[] = [
                'hari' => \Carbon\Carbon::now()->subDays($i)->translatedFormat('D'),
                'atm' => $tinggiAtm > 0 ? $tinggiAtm : 2, // Minimal tinggi 2% agar batang terlihat
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

    // 2. HALAMAN DATA BARANG
    public function index()
    {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    // 3. HALAMAN TRANSAKSI (Tampil Riwayat)
    public function transaksi()
    {
        $riwayat = DB::table('transaksis')->orderBy('created_at', 'desc')->get();
        return view('barang.barang_keluar', compact('riwayat'));
    }

    // 4. SIMPAN TRANSAKSI & UPDATE STOK
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

    // 5. TAMBAH BARANG BARU (Fix Field 'jenis')
    public function store(Request $request)
    {
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

    // 6. EDIT & HAPUS
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->update($request->all());
        return back()->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang berhasil dihapus!');
    }
    public function laporan()
    {
        $barang = \App\Models\Barang::all();
        $total_stok = \App\Models\Barang::sum('stok');

        return view('laporan', compact('barang', 'total_stok'));
    }
}
