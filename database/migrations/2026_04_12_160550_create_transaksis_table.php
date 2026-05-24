<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persediaan_id')->nullable()->constrained('persediaan')->onDelete('set null');
            $table->string('nama_barang');
            $table->enum('jenis', ['MASUK', 'KELUAR']);
            $table->integer('jumlah');
            $table->string('petugas');
            $table->date('tanggal');
            $table->string('keterangan')->nullable(); // Ditambahkan agar sinkron dengan Model & Controller
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
