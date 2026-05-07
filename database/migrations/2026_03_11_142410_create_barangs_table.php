<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            
            // Nama barang (Contoh: Kartu ATM GPN, Buku Tabungan Wadiah)
            $table->string('nama_barang'); 
            
            // Jenis barang (Otomatis: ATM atau Buku)
            $table->string('jenis'); 
            
            // Jumlah stok barang yang tersedia
            $table->integer('stok')->default(0); 
            
            // Satuan barang (Pcs, Box, dll)
            $table->string('satuan')->default('Pcs'); 
            
            // Keterangan tambahan (Contoh: Cetakan tahun 2026)
            $table->text('keterangan')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};