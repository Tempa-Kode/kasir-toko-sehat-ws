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
        Schema::create('tb_riwayat_produk_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('tb_produk', 'id')->onDelete('cascade');
            $table->integer('stok');
            $table->string('distributor', 50)->nullable();
            $table->timestamp('tanggal_masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat_produk_masuk');
    }
};
