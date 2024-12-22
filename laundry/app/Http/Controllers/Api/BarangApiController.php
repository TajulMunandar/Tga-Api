<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangApiController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return response()->json([
            'success' => true,
            'data' => $barangs
        ], 200);
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $barang->update([
            'stock' => $request->stock,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock berhasil diperbarui',
            'data' => $barang
        ], 200);
    }
}
