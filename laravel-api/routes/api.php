<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| File ini mendefinisikan rute-rute API untuk aplikasi Anda.
| Semua rute ini dimasukkan dalam grup namespace "api".
|
| Pastikan Anda menyesuaikan middleware jika diperlukan, seperti "auth:api".
*/

// Grup rute untuk manajemen data
Route::prefix('/management')->group(function () {
    // Melihat data pelanggan
    Route::get('/customers', [ManagementController::class, 'viewCustomers'])->name('api.management.customers.view');

    // Menambahkan stok barang
    Route::post('/stocks', [ManagementController::class, 'addStock'])->name('api.management.stocks.add');

    // Menambahkan tarif laundry
    Route::post('/laundry-rates', [ManagementController::class, 'addLaundryRate'])->name('api.management.laundry-rates.add');

    // Mendapatkan laporan transaksi
    Route::get('/transactions', [ManagementController::class, 'getTransactions'])->name('api.management.transactions.get');

    // Mengedit transaksi pelanggan berdasarkan ID
    Route::put('/transactions/{id}', [ManagementController::class, 'editTransaction'])->name('api.management.transactions.edit');
});

// Grup rute untuk API autentikasi
Route::prefix('/auth')->group(function () {
    // Registrasi kasir
    Route::post('/register/cashier', [AuthController::class, 'registerCashier'])->name('api.auth.register.cashier')->middleware([]);;

    // Registrasi pelanggan
    Route::post('/register/customer', [AuthController::class, 'registerCustomer'])->name('api.auth.register.customer');

    // Login kasir
    Route::post('/login/cashier', [AuthController::class, 'loginCashierApi'])->name('api.auth.login.cashier');

    // Login pelanggan
    Route::post('/login/customer', [AuthController::class, 'loginCustomer'])->name('api.auth.login.customer');

    // Logout (baik kasir maupun pelanggan)
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
});
