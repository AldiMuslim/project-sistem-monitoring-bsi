<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang', 
        'jenis', 
        'stok', 
        'satuan', 
        'keterangan'
    ];
}