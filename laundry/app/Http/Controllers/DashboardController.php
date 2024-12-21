<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Tarif;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Pelanggan::count();
        $totalBarangs = Barang::count();
        $totalLaundryRates = Tarif::count();
        $totalTransactions = Transaksi::count();
        return view('dashboard.index')->with(compact('totalCustomers', 'totalBarangs', 'totalLaundryRates', 'totalTransactions'));
    }
}
