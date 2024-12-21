<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class ManajemenStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
        return view('dashboard.management')->with(compact('barangs'));
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
        $request->validate([
            'kode' => 'required|string|unique:barangs',
            'barang' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
        ]);

        Barang::create([
            'kode' => $request->kode,
            'barang' => $request->barang,
            'stock' => $request->stock,
            'harga' => $request->harga,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|unique:barangs,kode,' . $id,
            'barang' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'kode' => $request->kode,
            'barang' => $request->barang,
            'stock' => $request->stock,
            'harga' => $request->harga,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
