<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kasir;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Cek apakah user ada dan password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah',
            ], 401);
        }

        // Tentukan role pengguna dan ambil profil yang sesuai
        $profile = null;

        if ($user->role == 2) {
            // Role kasir, ambil data kasir
            $profile = Kasir::where('id_user', $user->id)->first();
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kasir tidak ditemukan',
                ], 403);
            }

            if ($profile->status === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum diverifikasi',
                ], 403);
            }
        } elseif ($user->role == 3) {
            // Role pelanggan, ambil data pelanggan
            $profile = Pelanggan::where('id_user', $user->id)->first();
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelanggan tidak ditemukan',
                ], 403);
            }

            // Cek status pelanggan
            if ($profile->status === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum diverifikasi',
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak terdaftar',
            ], 403);
        }

        // Generate token untuk pengguna yang berhasil login
        $token = $user->createToken('LaravelApp')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'data' => [
                'user' => $user,
                'profile' => $profile,
                'token' => $token
            ]
        ], 200);
    }

    public function registerApi(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:1,2,3',
        ]);

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Buat data tambahan berdasarkan role
        if ($request->role == 2) {
            Kasir::create([
                'id_user' => $user->id,
                'kode' => 'KSR' . $user->id,
            ]);
        } elseif ($request->role == 3) {
            Pelanggan::create([
                'id_user' => $user->id,
                'kode' => 'PLG' . $user->id,
            ]);
        }

        // Response sukses
        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil!',
            'data' => [
                'user' => $user,
                'role_data' => $user->role == 1 ? 'Management' : ($user->role == 2 ? 'Kasir' : 'Pelanggan'),
            ],
        ], 201);
    }
}
