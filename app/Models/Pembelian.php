<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';

    protected $fillable = [
        'user_id',
        'supplier_id',
        'total_harga',
    ];

    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'pembelian_id');
    }
}
