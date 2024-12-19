<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagementController;

// Halaman Dashboard
Route::get('dashboard', [ManagementController::class, 'dashboard'])->name('dashboard');

// Halaman Data Pelanggan
Route::get('management/customers', [ManagementController::class, 'viewCustomers'])->name('management.customers');

// Halaman Manajemen Stok
Route::get('management/stocks', function () {
    return view('management.stocks'); // Pastikan tampilan ini ada
})->name('management.stocks');
Route::post('/stocks', [ManagementController::class, 'addStock'])->name('stocks.add');

// Halaman Tarif Laundry
Route::get('management/laundry-rates', function () {
    return view('management.laundry_rates'); // Pastikan tampilan ini ada
})->name('management.laundry_rates');
// Rute untuk menambahkan tarif laundry
Route::post('/laundry-rate', [ManagementController::class, 'addLaundryRate'])->name('laundry.rate.add');

// Halaman Laporan Transaksi
Route::get('management/transactions', [ManagementController::class, 'getTransactions'])->name('management.transactions');

// Halaman Login Kasir
Route::get('login/cashier', function () {
    return view('auth.login_cashier'); // Pastikan tampilan ini ada
})->name('login.cashier');

// Halaman Login Pelanggan
Route::get('login/customer', function () {
    return view('auth.login_customer'); // Pastikan tampilan ini ada
})->name('login.customer');

// Halaman Registrasi Kasir
Route::get('register/cashier', function () {
    return view('auth.register-cashier'); // Pastikan tampilan ini ada
})->name('register.cashier.form');

// Proses Registrasi Kasir
Route::post('register/cashier', [AuthController::class, 'registerCashier'])->name('register.cashier');

// Halaman Registrasi Pelanggan
Route::get('register/customer', function () {
    return view('auth.register-customer'); // Pastikan tampilan ini ada
})->name('register.customer.form');

// Proses Registrasi Pelanggan
Route::post('register/customer', [AuthController::class, 'registerCustomer'])->name('register.customer');

// Rute untuk Logout Kasir
Route::post('cashier/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Rute untuk Verifikasi Kasir
Route::post('cashier/verify', [AuthController::class, 'verifyCashier'])->name('auth.verify_cashier');

// Rute untuk Verifikasi Pelanggan
Route::post('customer/verify', [AuthController::class, 'verifyCustomer'])->name('auth.verify_customer');

// Rute untuk Logout Pelanggan
Route::post('customer/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Rute untuk mengedit transaksi
Route::get('management/transactions/edit/{id}', [ManagementController::class, 'editTransactionForm'])->name('management.transactions.edit.form');
Route::post('management/transactions/edit/{id}', [ManagementController::class, 'editTransaction'])->name('management.transactions.edit');
