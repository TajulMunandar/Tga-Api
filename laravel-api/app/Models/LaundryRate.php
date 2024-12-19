<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryRate extends Model
{
    use HasFactory;

    // Menentukan atribut yang dapat diisi
    protected $fillable = [
        'service_name',
        'rate',
    ];
}

