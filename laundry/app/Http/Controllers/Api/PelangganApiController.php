<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PelangganApiController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id'); // Mendapatkan user_id langsung dari request

        // Ambil data pelanggan berdasarkan user_id yang diberikan
        $pelanggan = Pelanggan::where('id_user', $userId)->first();


        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan tidak ditemukan'
            ], 404);
        }

        $user = User::where('id', $userId)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil diambil.',
            'data' => [
                'name' => $user->name,         // Nama user
                'username' => $user->username, // Username user
                'alamat' => $pelanggan->alamat,
                'no_hp' => $pelanggan->no_hp
            ]
        ], 200);
    }

    public function updatePelanggan(Request $request, $id)
    {

        $request->validate([
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
        ]);

        // Mendapatkan data pelanggan berdasarkan ID user
        $pelanggan = Pelanggan::where('id_user', $id)->first();

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan tidak ditemukan'
            ], 404);
        }

        // Perbarui data
        $pelanggan->alamat = $request->input('alamat');
        $pelanggan->no_hp = $request->input('no_hp');
        $pelanggan->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil pelanggan berhasil diperbarui',
            'data' => $pelanggan
        ], 200);
    }

    public function getData()
    {
        $pelanggans = Pelanggan::with('user')->get(); // Memastikan relasi user ikut diambil
        $data = $pelanggans->map(function ($pelanggan) {
            return [
                'name' => $pelanggan->user->name ?? 'Unknown', // Nama user
                'username' => $pelanggan->user->username ?? 'Unknown', // Username user
                'kode' => $pelanggan->kode,
                'alamat' => $pelanggan->alamat,
                'no_hp' => $pelanggan->no_hp,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil diambil.',
            'data' => $data,
        ], 200);
    }
}
