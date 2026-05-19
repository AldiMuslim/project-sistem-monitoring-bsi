<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class BarangController extends Controller
{
    /**
     * DASHBOARD DINAMIS PREMIUM
     * Mengukur Tren Barang Masuk vs Keluar Selama 7 Hari Terakhir
     */
    public function dashboard()
    {
        $totalJenisBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())->count();
        
        $itemMasukHariIni = Transaksi::whereDate('created_at', Carbon::today())->where('jenis', 'MASUK')->sum('jumlah');
        $itemKeluarHariIni = Transaksi::whereDate('created_at', Carbon::today())->where('jenis', 'KELUAR')->sum('jumlah');
        
        $stokMenipisCount = Barang::where('stok', '<=', 5)->count();
        $recentTransaksi = Transaksi::orderBy('created_at', 'desc')->limit(5)->get();

        // --- TAMBAHAN HITUNGAN GRAFIK DONAT (POIN 1) ---
        $stokATM = Barang::where('nama_barang', 'LIKE', '%ATM%')->sum('stok') ?? 0;
        $stokBuku = Barang::where('nama_barang', 'LIKE', '%BUKU%')->sum('stok') ?? 0;
        
        $totalAsetTerhitung = $stokATM + $stokBuku;
        $persenATM = $totalAsetTerhitung > 0 ? round(($stokATM / $totalAsetTerhitung) * 100) : 0;
        $persenBuku = $totalAsetTerhitung > 0 ? (100 - $persenATM) : 0; // Memastikan total pas 100%

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
            'totalJenisBarang',
            'totalStok',
            'transaksiHariIni',
            'itemMasukHariIni',
            'itemKeluarHariIni',
            'stokMenipisCount',
            'recentTransaksi',
            'chartData',
            // Kirim variabel donat ke view
            'persenATM',
            'persenBuku',
            'stokATM',
            'stokBuku'
        ));
    }

    /**
     * HALAMAN DATA BARANG
     * Dapat diakses oleh Admin dan Petugas (Read-Only untuk Petugas)
     */
    /**
     * HALAMAN DATA BARANG
     * Mendukung Pencarian, Paginasi, dan Filter Stok Menipis
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter'); // <-- Tambahan untuk menangkap parameter filter

        $barangs = Barang::when($search, function ($query, $search) {
            // Mengelompokkan query pencarian agar tidak bentrok dengan filter
            return $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%")
                    ->orWhere('jenis', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        })
            // --- KUNCI FILTER: Jika ada request filter 'menipis', saring yang stoknya <= 5 ---
            ->when($filter === 'menipis', function ($query) {
                return $query->where('stok', '<=', 5);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

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

        // Menambahkan 'jenis' ke dalam baris validasi wajib
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis'       => 'required|string',
            'stok'        => 'required|numeric|min:0',
            'satuan'      => 'required|string',
            'keterangan'  => 'nullable|string',
        ]);

        // Perbaikan: Murni menyimpan data 'jenis' sesuai pilihan dropdown modal kustom BSI Anda
        Barang::create([
            'nama_barang' => $request->nama_barang,
            'jenis'       => $request->jenis,
            'stok'        => $request->stok,
            'satuan'      => $request->satuan,
            'keterangan'  => $request->keterangan,
        ]);

        return back()->with('success', 'Barang baru berhasil disimpan ke gudang!');
    }

    /**
     * UPDATE DATA BARANG
     * KHUSUS ADMIN
     */
    public function update(Request $request, $id)
    {
        // Proteksi Gate: Hanya Admin
        Gate::authorize('manage-users');

        // Menambahkan validasi ketat sebelum update data dilakukan
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis'       => 'required|string',
            'stok'        => 'required|numeric|min:0',
            'satuan'      => 'required|string',
            'keterangan'  => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($id);

        // Perbaikan: Mengupdate data secara spesifik agar sinkron dengan dropdown modal
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'jenis'       => $request->jenis,
            'stok'        => $request->stok,
            'satuan'      => $request->satuan,
            'keterangan'  => $request->keterangan,
        ]);

        return back()->with('success', 'Data barang berhasil diperbarui!');
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
     * HALAMAN TRANSAKSI (MUTASI BARANG)
     * Mendukung Pencarian, Filter Jenis Mutasi, & Paginasi
     */
    public function transaksi(Request $request)
    {
        // 1. Tangkap parameter filter dari URL
        $search = $request->input('search');
        $jenisFilter = $request->input('jenis_filter');

        // 2. Query data riwayat transaksi mutasi gudang dengan filter dinamis
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
            ->paginate(10)                  // Batasi 10 data per halaman
            ->withQueryString();            // Mempertahankan filter saat pindah halaman

        // 3. Ambil data barang aktif untuk keperluan Dropdown Dinamis di modal
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();

        return view('barang.barang_keluar', compact('riwayat', 'barangs'));
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
}
