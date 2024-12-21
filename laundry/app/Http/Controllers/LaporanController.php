<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Transaksi::all();
        return view('dashboard.laporan')->with(compact('laporans'));
    }
}
