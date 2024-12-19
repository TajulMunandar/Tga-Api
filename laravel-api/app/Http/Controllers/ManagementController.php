<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\LaundryRate;
use App\Models\Transaction;

class ManagementController extends Controller
{
    // Fungsi untuk melihat data pelanggan
    public function viewCustomers()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    // Fungsi untuk memasukkan stok barang
    public function addStock(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = Stock::create([
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('management.stocks')->with('success', 'Stok berhasil ditambahkan.');
    }

    // Fungsi untuk memasukkan tarif laundry
    public function addLaundryRate(Request $request)
{
    $request->validate([
        'service_name' => 'required|string|max:255',
        'rate' => 'required|numeric|min:0',
    ]);

    try {
        $laundryRate = LaundryRate::create([
            'service_name' => $request->service_name,
            'rate' => $request->rate,
        ]);
        return redirect()->route('management.laundry_rates')->with('success', 'Tarif Laundry berhasil ditambahkan.');
    } catch (\Exception $e) {
        // Menampilkan pesan kesalahan jika ada
        return back()->withErrors(['error' => 'Gagal menambahkan tarif laundry: ' . $e->getMessage()]);
    }
}

    // Fungsi untuk menerima laporan transaksi dari kasir
    public function getTransactions()
    {
        // Ambil semua transaksi yang sudah terinput
        $transactions = Transaction::with(['customer', 'laundryRate'])->get();
        return response()->json($transactions);
    }

    // Fungsi untuk mengedit transaksi pelanggan
    public function editTransaction(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'laundry_rate_id' => 'required|exists:laundry_rates,id',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        // Cari transaksi berdasarkan ID
        $transaction = Transaction::find($id);
        
        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Update transaksi
        $transaction->update([
            'customer_id' => $request->customer_id,
            'laundry_rate_id' => $request->laundry_rate_id,
            'amount_paid' => $request->amount_paid,
        ]);

        return response()->json(['message' => 'Transaksi berhasil diubah', 'transaction' => $transaction]);
    }

    public function dashboard()
    {
        $totalCustomers = Customer::count();
        $totalStocks = Stock::sum('quantity');
        $totalLaundryRates = LaundryRate::count();
        $totalTransactions = Transaction::count();

        return view('dashboard', compact('totalCustomers', 'totalStocks', 'totalLaundryRates', 'totalTransactions'));
    }

    // Metode lain yang sudah ada seperti viewCustomers, addStock, dll.
}
