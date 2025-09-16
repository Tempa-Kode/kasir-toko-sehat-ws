<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'tb_transaksi';

    protected $fillable = [
        'no_nota',
        'tgl_transaksi',
        'harga_total',
        'dibayar',
        'kembalian',
        'kasir_id',
    ];

    public function kasir() : BelongsTo
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
}
