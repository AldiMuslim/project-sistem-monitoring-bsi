<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes; // <-- 2. Wajib Pasang Trait Di Sini
    protected $fillable = [
        'nama_barang',
        'jenis',
        'stok',
        'satuan',
        'keterangan'
    ];
}
