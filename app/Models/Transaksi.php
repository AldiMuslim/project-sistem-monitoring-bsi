<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    // Hapus atau ganti baris ini menjadi 'transaksis'
    protected $table = 'transaksis'; 

    protected $fillable = [
        'barang_id',
        'jenis_transaksi', // Pastikan menggunakan nama kolom yang benar sesuai database (sebelumnya Anda tulis 'jenis')
        'jumlah',
        'tanggal',
        'keterangan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}