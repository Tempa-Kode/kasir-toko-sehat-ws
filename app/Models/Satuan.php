<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'tb_satuan';
    protected $fillable = ['kode_satuan', 'nama_satuan', 'deskripsi'];
}
