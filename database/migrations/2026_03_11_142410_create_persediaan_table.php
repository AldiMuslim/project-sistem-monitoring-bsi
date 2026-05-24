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
        // 1. Nama tabel diubah menjadi 'persediaan'
        Schema::create('persediaan', function (Blueprint $table) {
            $table->id();
            
            // 2. Diubah menjadi nama_persediaan agar konsisten dengan nama tabel
            $table->string('nama_persediaan'); 
            
            // Jenis persediaan (ATM atau Buku)
            $table->string('jenis'); 
            
            // Jumlah stok persediaan yang tersedia
            $table->integer('stok')->default(0); 
            
            // Satuan persediaan (Pcs, Box, dll)
            $table->string('satuan')->default('Pcs'); 
            
            // Keterangan tambahan (Contoh: Cetakan tahun 2026)
            $table->text('keterangan')->nullable(); 
            
            $table->timestamps();
            
            // 3. Langsung tambahkan softDeletes di sini agar tidak memerlukan file migrasi tambahan
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persediaan');
    }
};