<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tarifs = Tarif::all();
        return view('dashboard.tarif')->with(compact('tarifs'));
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
            'kode' => 'required|string|unique:tarifs',
            'nama_layanan' => 'required|string|max:255',
            'tarif' => 'required|integer|min:0',
        ]);

        Tarif::create([
            'kode' => $request->kode,
            'nama_layanan' => $request->nama_layanan,
            'tarif' => $request->tarif,
        ]);

        return redirect()->route('tarif.index')->with('success', 'tarif berhasil ditambahkan!');
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
            'kode' => 'required|string|unique:tarifs,kode,' . $id,
            'nama_layanan' => 'required|string|max:255',
            'tarif' => 'required|integer|min:0',
        ]);

        $tarif = Tarif::findOrFail($id);
        $tarif->update([
            'kode' => $request->kode,
            'nama_layanan' => $request->nama_layanan,
            'tarif' => $request->tarif,
        ]);

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tarif = Tarif::findOrFail($id);
        $tarif->delete();

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dihapus!');
    }
}
