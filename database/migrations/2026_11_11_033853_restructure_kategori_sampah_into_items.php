<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Old category taxonomy doesn't map to the new one, so existing
        // transaction history can't be preserved across this restructure.
        DB::table('transaksi_tarik')->truncate();
        DB::table('transaksi_setor')->truncate();

        Schema::table('transaksi_setor', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });

        Schema::table('kategori_sampah', function (Blueprint $table) {
            $table->dropColumn(['harga_per_kg', 'satuan']);
        });

        DB::table('kategori_sampah')->truncate();

        Schema::create('item_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_sampah')->restrictOnDelete();
            $table->string('nama_item', 150);
            $table->decimal('harga_per_kg', 14, 0);
            $table->string('satuan', 20)->default('kg');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['kategori_id', 'nama_item']);
        });

        Schema::table('transaksi_setor', function (Blueprint $table) {
            $table->foreignId('item_id')->after('nasabah_id')->constrained('item_sampah')->restrictOnDelete();
        });

        DB::table('nasabah')->update(['saldo' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_setor', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn('item_id');
        });

        Schema::dropIfExists('item_sampah');

        Schema::table('kategori_sampah', function (Blueprint $table) {
            $table->decimal('harga_per_kg', 14, 0)->default(0);
            $table->string('satuan', 20)->default('kg');
        });

        Schema::table('transaksi_setor', function (Blueprint $table) {
            $table->foreignId('kategori_id')->after('nasabah_id')->constrained('kategori_sampah')->restrictOnDelete();
        });
    }
};
