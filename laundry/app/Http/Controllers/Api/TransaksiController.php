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
use Illuminate\Support\Facades\Log;

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

        $pelanggan = Pelanggan::where('id_user', $userId)->first();

        if (!$pelanggan) {
            return response()->json([
                'message' => 'Pelanggan tidak ditemukan'
            ], 404);
        }

        // Mengambil data transaksi berdasarkan user_id dan status = 1, diurutkan berdasarkan yang terbaru
        $transaksi = Transaksi::where('id_pelanggan', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$transaksi) {
            return response()->json([
                'message' => 'Tidak ada transaksi'
            ], 404);
        }

        return response()->json([
            'data' => [
                'kode' => $transaksi->kode,
                'tanggal_masuk' => $transaksi->tanggal_masuk,
                'tanggal_selesai' => $transaksi->tanggal_selesai,
                'berat' => $transaksi->berat,
                'jenis' => $transaksi->jenis, // Asumsikan ini int (1 atau 0)
                'status' => $transaksi->status, // Asumsikan ini int (1 atau 0)
            ],
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
                'id_pelanggan' => $id_pelanggan->id,
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

    public function getDataTransaksi()
    {
        // Ambil semua transaksi dengan status 0
        $transaksis = Transaksi::with(['Pelanggan.User', 'Tarif', 'TransaksiDetail.Barang'])
            ->where('status', 0)
            ->get();

        return response()->json($transaksis);
    }

    public function updateStatus($kode, Request $request)
    {
        // Cari transaksi berdasarkan kode
        $transaksi = Transaksi::where('kode', $kode)->first();

        if ($transaksi) {
            // Update status transaksi
            $transaksi->status = $request->input('status');
            $transaksi->save();

            // Hitung total biaya berdasarkan berat dan tarif
            $tarif = $transaksi->tarif->tarif;

            // Menghitung total biaya berdasarkan berat dan tarif, serta harga barang dari TransaksiDetail
            $totalBiaya = ($transaksi->berat * $tarif); // Biaya berdasarkan berat dan tarif

            // Menambahkan biaya barang dari setiap TransaksiDetail
            foreach ($transaksi->TransaksiDetail as $detail) {
                $totalBiaya += $detail->Barang->harga; // Menambahkan harga setiap barang
            }

            return response()->json([
                'message' => 'Status transaksi berhasil diubah',
                'total_biaya' => $totalBiaya
            ]);
        } else {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }
    }
}
