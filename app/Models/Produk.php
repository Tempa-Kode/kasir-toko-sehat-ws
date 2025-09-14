<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    protected $table = 'tb_produk';

    protected $fillable = [
        'satuan_id',
        'kategori_id',
        'kode_produk',
        'nama_produk',
        'harga',
        'stok',
    ];

    public function satuan() : BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function kategori() : BelongsTo
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }
}
