<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BarangApiController;
use App\Http\Controllers\Api\PelangganApiController;
use App\Http\Controllers\Api\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/registerApi', [AuthApiController::class, 'registerApi']);
Route::get('/profile', [PelangganApiController::class, 'index']);
Route::patch('/profile/{id}/pelanggan', [PelangganApiController::class, 'updatePelanggan']);
Route::get('/pelanggan', [PelangganApiController::class, 'index']);
Route::get('/transaksi', [TransaksiController::class, 'getData']);
Route::get('/transaksiData', [TransaksiController::class, 'getDataForm']);
Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::get('/transaksi-list', [TransaksiController::class, 'getDataTransaksi']);
Route::put('/update-status/{kode}', [TransaksiController::class, 'updateStatus']);


Route::get('/barangs', [BarangApiController::class, 'index']); // Ambil semua barang
Route::patch('/barangs/{id}/stock', [BarangApiController::class, 'updateStock']); // Update stock
