<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satuan extends Model
{
    protected $table = 'tb_satuan';
    protected $fillable = ['kode_satuan', 'nama_satuan', 'deskripsi'];

    public function produk() : HasMany
    {
        return $this->hasMany(Produk::class, 'satuan_id');
    }
}
