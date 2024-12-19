<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'laundry_rate_id', 'amount_paid'];

    // Relasi dengan tabel Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi dengan tabel LaundryRate
    public function laundryRate()
    {
        return $this->belongsTo(LaundryRate::class);
    }
}
