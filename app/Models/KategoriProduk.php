<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'tb_kategori_produk';
    protected $fillable = ['nama_kategori', 'deskripsi'];
}
