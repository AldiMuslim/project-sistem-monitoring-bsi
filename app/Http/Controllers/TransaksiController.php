<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{

public function index()
{

$barang = Barang::all();

$transaksi = Transaksi::with('barang')->get();

return view('transaksi.index',compact('barang','transaksi'));

}

public function store(Request $request)
{

$barang = Barang::find($request->barang_id);

if($request->jenis=="Masuk")
{
$barang->stok += $request->jumlah;
}

else
{
$barang->stok -= $request->jumlah;
}

$barang->save();

Transaksi::create([
'barang_id'=>$request->barang_id,
'jenis'=>$request->jenis,
'jumlah'=>$request->jumlah,
'tanggal'=>$request->tanggal,
'keterangan'=>$request->keterangan
]);

return redirect('/transaksi');

}

}