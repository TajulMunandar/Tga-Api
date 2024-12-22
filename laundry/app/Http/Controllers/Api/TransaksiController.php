<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function getData(Request $request)
    {
        // Ambil user_id dari parameter request
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'message' => 'User ID tidak ditemukan'
            ], 400);
        }

        // Mengambil data transaksi berdasarkan user_id dan status = 1, diurutkan berdasarkan yang terbaru
        $transaksis = Transaksi::where('id_pelanggan', $userId)
            ->where('status', 1)
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan created_at yang terbaru
            ->first();

        return response()->json([
            'data' => $transaksis,
        ]);
    }

    public function getDataForm()
    {
        // Mengambil semua data barang dan tarif
        $barangs = Barang::all();
        $tarifs = Tarif::all();

        // Mengembalikan data barang dan tarif dalam satu response
        return response()->json([
            'barangs' => $barangs,
            'tarifs' => $tarifs
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan.',
            ], 400);
        }

        // Cek apakah pelanggan ada dengan id_user tersebut
        $id_pelanggan = Pelanggan::where('id_user', $userId)->first();
        if (!$id_pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan tidak ditemukan.',
            ], 400);
        }

        try {
            // Membuat transaksi baru
            $transaksi = Transaksi::create([
                'kode' => $request->kode,
                'id_pelanggan' => $id_pelanggan,
                'id_tarif' => $request->id_tarif,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_selesai' => $request->tanggal_selesai,
                'berat' => $request->berat,
                'status' => 0,
                'jenis' => $request->jenis,
            ]);

            // Membuat transaksi detail berdasarkan data yang dikirimkan
            foreach ($request->transaksi_details as $detail) {
                TransaksiDetail::create([
                    'id_transaksi' => $transaksi->id,
                    'id_barang' => $detail['id_barang'],
                    'stock' => $detail['stock'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi dan detail berhasil disimpan.',
                'data' => $transaksi,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan transaksi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
