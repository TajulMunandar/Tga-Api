<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
