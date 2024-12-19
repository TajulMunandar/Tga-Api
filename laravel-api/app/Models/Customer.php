<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Customer extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang digunakan oleh model ini
    protected $table = 'customers';

    // Tentukan kolom yang dapat diisi (fillable)
    protected $fillable = ['name', 'email', 'password', 'role'];

    // Kolom yang disembunyikan (misalnya, password) saat mengambil data
    protected $hidden = ['password'];

    // Menambahkan mutator untuk memastikan password selalu di-hash sebelum disimpan
    public function setPasswordAttribute($value)
    {
        // Memastikan password selalu di-hash
        $this->attributes['password'] = Hash::make($value);
    }

    // Opsional: Jika ingin menambahkan atribut virtual, misalnya full_name
    // public function getFullNameAttribute()
    // {
    //     return $this->attributes['name'] . ' (customer)';
    // }
}
