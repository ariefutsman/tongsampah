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
        Schema::create('transaksi_setor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori_sampah')->restrictOnDelete();
            $table->decimal('berat_kg', 8, 2);
            $table->decimal('total_nilai', 14, 0);
            $table->date('tanggal');
            $table->timestamps();

            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_setor');
    }
};
