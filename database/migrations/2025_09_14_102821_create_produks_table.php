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
        Schema::create('tb_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satuan_id')->constrained('tb_satuan')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('tb_kategori_produk')->onDelete('cascade');
            $table->string('kode_produk', 100)->unique();
            $table->string('nama_produk', 100);
            $table->decimal('harga', 10, 2);
            $table->bigInteger('stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_produk');
    }
};
