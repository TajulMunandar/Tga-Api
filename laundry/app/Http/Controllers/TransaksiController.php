<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tarif;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['Pelanggan', 'Tarif'])->get();
        return view('dashboard.transaksi.transaksi', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $pelanggans = Pelanggan::all();
        $tarifs = Tarif::all();
        return view('dashboard.transaksi.edit', compact('transaksi', 'pelanggans', 'tarifs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'kode' => 'required|unique:transaksis,kode,' . $transaksi->id,
            'id_pelanggan' => 'required|exists:pelanggans,id',
            'id_tarif' => 'required|exists:tarifs,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'berat' => 'required|integer',
            'status' => 'required|boolean',
            'jenis' => 'required|boolean',
        ]);

        $transaksi->update($request->all());

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
