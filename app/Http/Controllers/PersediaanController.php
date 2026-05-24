<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persediaan; // 1. Diubah dari App\Models\Barang
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class PersediaanController extends Controller // 2. Diubah dari BarangController
{
    /**
     * DASHBOARD DINAMIS PREMIUM
     * Mengukur Tren Persediaan Masuk vs Keluar Selama 7 Hari Terakhir
     */
    public function dashboard()
    {
        $totalJenisPersediaan = Persediaan::count(); // Diubah dari Barang
        $totalStok = Persediaan::sum('stok'); // Diubah dari Barang
        $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())->count();

        $itemMasukHariIni = Transaksi::whereDate('created_at', Carbon::today())->where('jenis', 'MASUK')->sum('jumlah');
        $itemKeluarHariIni = Transaksi::whereDate('created_at', Carbon::today())->where('jenis', 'KELUAR')->sum('jumlah');

        $stokMenipisCount = Persediaan::where('stok', '<=', 5)->count(); // Diubah dari Barang
        $recentTransaksi = Transaksi::orderBy('created_at', 'desc')->limit(5)->get();

        // --- TAMBAHAN HITUNGAN GRAFIK DONAT ---
        // Kolom dicari berdasarkan 'nama_persediaan'
        $stokATM = Persediaan::where('nama_persediaan', 'LIKE', '%ATM%')->sum('stok') ?? 0;
        $stokBuku = Persediaan::where('nama_persediaan', 'LIKE', '%BUKU%')->sum('stok') ?? 0;

        $totalAsetTerhitung = $stokATM + $stokBuku;
        $persenATM = $totalAsetTerhitung > 0 ? round(($stokATM / $totalAsetTerhitung) * 100) : 0;
        $persenBuku = $totalAsetTerhitung > 0 ? (100 - $persenATM) : 0;

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $jumlahMasuk = Transaksi::whereDate('created_at', $date)->where('jenis', 'MASUK')->sum('jumlah');
            $jumlahKeluar = Transaksi::whereDate('created_at', $date)->where('jenis', 'KELUAR')->sum('jumlah');

            $tinggiMasuk = min(($jumlahMasuk / 50) * 100, 100);
            $tinggiKeluar = min(($jumlahKeluar / 50) * 100, 100);

            $chartData[] = [
                'hari'        => Carbon::now()->subDays($i)->translatedFormat('D'),
                'masuk'       => $tinggiMasuk > 0 ? $tinggiMasuk : 2,
                'keluar'      => $tinggiKeluar > 0 ? $tinggiKeluar : 2,
                'asli_masuk'  => (int) $jumlahMasuk,
                'asli_keluar' => (int) $jumlahKeluar
            ];
        }

        return view('dashboard', compact(
            'totalJenisPersediaan',
            'totalStok',
            'transaksiHariIni',
            'itemMasukHariIni',
            'itemKeluarHariIni',
            'stokMenipisCount',
            'recentTransaksi',
            'chartData',
            'persenATM',
            'persenBuku',
            'stokATM',
            'stokBuku'
        ));
    }

    /**
     * HALAMAN DATA PERSEDIAAN
     * Mendukung Pencarian, Paginasi, dan Filter Stok Menipis
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');

        $persediaans = Persediaan::when($search, function ($query, $search) { // Diubah dari Barang
            return $query->where(function ($q) use ($search) {
                $q->where('nama_persediaan', 'LIKE', "%{$search}%") // Diubah dari nama_barang
                    ->orWhere('jenis', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        })
            ->when($filter === 'menipis', function ($query) {
                return $query->where('stok', '<=', 5);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('persediaan.index', compact('persediaans')); // Target folder view diarahkan ke folder persediaan
    }

    /**
     * SIMPAN PERSEDIAAN BARU
     * KHUSUS ADMIN
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'nama_persediaan' => 'required|string|max:255', // Diubah dari nama_barang
            'jenis'           => 'required|string',
            'stok'            => 'required|numeric|min:0',
            'satuan'          => 'required|string',
            'keterangan'      => 'nullable|string',
        ]);

        Persediaan::create([ // Diubah dari Barang
            'nama_persediaan' => $request->nama_persediaan,
            'jenis'           => $request->jenis,
            'stok'            => $request->stok,
            'satuan'          => $request->satuan,
            'keterangan'      => $request->keterangan,
        ]);

        return back()->with('success', 'Persediaan baru berhasil disimpan ke gudang!');
    }

    /**
     * UPDATE DATA PERSEDIAAN
     * KHUSUS ADMIN
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'nama_persediaan' => 'required|string|max:255', // Diubah dari nama_barang
            'jenis'           => 'required|string',
            'stok'            => 'required|numeric|min:0',
            'satuan'          => 'required|string',
            'keterangan'      => 'nullable|string',
        ]);

        $persediaan = Persediaan::findOrFail($id); // Diubah dari Barang

        $persediaan->update([
            'nama_persediaan' => $request->nama_persediaan,
            'jenis'           => $request->jenis,
            'stok'            => $request->stok,
            'satuan'          => $request->satuan,
            'keterangan'      => $request->keterangan,
        ]);

        return back()->with('success', 'Data persediaan berhasil diperbarui!');
    }

    /**
     * HAPUS PERSEDIAAN
     * KHUSUS ADMIN
     */
    public function destroy($id)
    {
        Gate::authorize('manage-users');

        Persediaan::findOrFail($id)->delete(); // Diubah dari Barang
        return back()->with('success', 'Persediaan berhasil dihapus!');
    }

    /**
     * HALAMAN TRANSAKSI (MUTASI PERSEDIAAN)
     * Mendukung Pencarian, Filter Jenis Mutasi, & Paginasi
     */
    public function transaksi(Request $request)
    {
        $search = $request->input('search');
        $jenisFilter = $request->input('jenis_filter');

        // NB: Pencarian riwayat mutasi tetap mengacu pada nama kolom asli tabel 'transaksis' (nama_barang)
        $riwayat = DB::table('transaksis')
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

        // Mengambil pilihan daftar untuk dropdown dari model Persediaan
        $persediaans = Persediaan::orderBy('nama_persediaan', 'asc')->get(); // Diubah dari Barang

        return view('persediaan.barang_keluar', compact('riwayat', 'persediaans')); // Folder view dialihkan ke persediaan
    }

    /**
     * SIMPAN TRANSAKSI & UPDATE STOK
     */
    public function storeTransaksi(Request $request)
    {
        $request->validate([
            'nama_persediaan' => 'required', // Diubah dari nama_barang
            'jenis'           => 'required',
            'jumlah'          => 'required|numeric|min:1',
        ]);

        $persediaan = Persediaan::where('nama_persediaan', $request->nama_persediaan)->first(); // Diubah dari Barang

        if ($persediaan) {
            if ($request->jenis == 'MASUK') {
                $persediaan->stok += $request->jumlah;
            } else {
                if ($persediaan->stok < $request->jumlah) {
                    return back()->with('error', 'Stok tidak cukup!');
                }
                $persediaan->stok -= $request->jumlah;
            }

            $persediaan->save();

            // Menyimpan riwayat log mutasi ke tabel 'transaksis'
            DB::table('transaksis')->insert([
                'nama_barang' => $request->nama_persediaan, // Kolom tabel transaksis diisi nilai nama_persediaan
                'jenis'       => $request->jenis,
                'jumlah'      => $request->jumlah,
                'petugas'     => auth()->user()->name,
                'tanggal'     => $request->tanggal ?? now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return back()->with('success', 'Transaksi berhasil disimpan!');
        }
        return back()->with('error', 'Persediaan tidak ditemukan!');
    }
}
