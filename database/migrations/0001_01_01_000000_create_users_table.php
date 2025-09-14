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
        Schema::create('tb_pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->enum('hak_akses', ['admin', 'pemilik', 'kasir']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
