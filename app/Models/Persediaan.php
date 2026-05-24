<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persediaan extends Model
{
    use HasFactory, SoftDeletes; // Trait bawaan dari model lama Anda

    // 1. Wajib tentukan nama tabel secara eksplisit agar Laravel tidak mencari nama tabel jamak "persediaans"
    protected $table = 'persediaan'; 

    // 2. Menyesuaikan kolom yang dapat diisi (fillable)
    protected $fillable = [
        'nama_persediaan', // Diubah dari 'nama_barang' jika Anda juga mengubah nama kolomnya di database
        'jenis',
        'stok',
        'satuan',
        'keterangan'
    ];
}