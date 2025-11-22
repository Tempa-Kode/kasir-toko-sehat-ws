<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatProdukMasuk extends Model
{
    protected $table = 'tb_riwayat_produk_masuk';

    protected $fillable = [
        'produk_id',
        'stok',
        'distributor',
        'tanggal_masuk',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
