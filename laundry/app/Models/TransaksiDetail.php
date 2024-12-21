<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function Transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }
}
