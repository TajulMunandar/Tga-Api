<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Cashier extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Nama tabel yang digunakan oleh model
    protected $table = 'cashiers';

    // Kolom-kolom yang dapat diisi (fillable)
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Menyembunyikan kolom sensitif (misalnya, password) dari array hasil JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Menentukan tipe atribut untuk email_verified_at
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Mutator untuk password agar selalu di-hash sebelum disimpan.
     */
    public function setPasswordAttribute($value)
    {
        // Pastikan password di-hash menggunakan Hash::make
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Menambahkan role default jika tidak diatur.
     */
    public function setRoleAttribute($value)
    {
        // Jika tidak ada nilai role yang diberikan, set default ke 'kasir'
        $this->attributes['role'] = $value ?: 'kasir';
    }
}
