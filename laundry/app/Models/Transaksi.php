<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function Pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function Tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif');
    }

    public function TransaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_transaksi');
    }
}
