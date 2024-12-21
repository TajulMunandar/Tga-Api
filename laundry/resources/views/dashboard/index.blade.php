@extends('dashboard.layout.main')

@section('content')
    <div class="card">
        <h3>Total Pelanggan</h3>
        <p>{{ $totalCustomers ?? 0 }}</p>
    </div>
    <div class="card">
        <h3>Total Barang</h3>
        <p>{{ $totalBarang ?? 0 }}</p>
    </div>
    <div class="card">
        <h3>Total Tarif Laundry</h3>
        <p>{{ $totalLaundryRates ?? 0 }}</p>
    </div>
    <div class="card">
        <h3>Total Transaksi</h3>
        <p>{{ $totalTransactions ?? 0 }}</p>
    </div>
@endsection
