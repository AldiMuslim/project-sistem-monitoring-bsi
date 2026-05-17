<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'barang_id',
        'nama_barang', // Tambahkan ini agar bisa disimpan lewat model
        'jenis',       // Sesuaikan dengan Migration
        'jumlah',
        'petugas',     // Tambahkan ini
        'tanggal',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'integer',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
