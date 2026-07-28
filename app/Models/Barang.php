<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'barcode',
        'nama_barang',
        'kategori_id',
        'supplier_id',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
    ];
}
