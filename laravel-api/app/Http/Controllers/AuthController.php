<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Fungsi registrasi kasir
    public function registerCashier(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:cashiers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Menyimpan kasir baru dengan role 'kasir'
        $cashier = Cashier::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir',  // Menetapkan role kasir
        ]);

        // Login kasir setelah registrasi
        Auth::login($cashier);

        return redirect()->route('dashboard')->with('message', 'Kasir berhasil terdaftar dan login');
        return redirect()->route('login.cashier')->with('message', 'Kasir berhasil terdaftar');
    }

    // Fungsi registrasi pelanggan
    public function registerCustomer(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Menyimpan pelanggan baru dengan role 'pelanggan'
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',  // Menetapkan role pelanggan
        ]);

        // Login pelanggan setelah registrasi
        Auth::login($customer);

        return redirect()->route('dashboard')->with('message', 'Pelanggan berhasil terdaftar dan login');
        return redirect()->route('login.customer')->with('message', 'Kasir berhasil terdaftar');
    }

    // Verifikasi login kasir
    public function loginCashier(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Mencari kasir berdasarkan email
        $cashier = Cashier::where('email', $request->email)->first();

        if ($cashier && Hash::check($request->password, $cashier->password)) {
            // Jika kasir terverifikasi, login
            Auth::login($cashier);
            return redirect()->route('dashboard')->with('message', 'Kasir berhasil login');
        }

        // Jika login gagal
        return back()->with('error', 'Email atau password salah');
    }

    // Verifikasi login pelanggan
    public function loginCustomer(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Mencari pelanggan berdasarkan email
        $customer = Customer::where('email', $request->email)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            // Jika pelanggan terverifikasi, login
            Auth::login($customer);
            return redirect()->route('dashboard')->with('message', 'Pelanggan berhasil login');
        }

        // Jika login gagal
        return back()->with('error', 'Email atau password salah');
    }

    // Logout kasir atau pelanggan
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.cashier');  // Ganti dengan rute login yang sesuai
    }

    public function loginCashierApi(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Mencari kasir berdasarkan email
        $cashier = Cashier::where('email', $request->email)->first();

        if (!$cashier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kasir dengan email ini tidak ditemukan',
            ], 404);
        }

        // Debugging: Periksa apakah password cocok
        if (!Hash::check($request->password, $cashier->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password salah',
            ], 401);
        }

        // Buat token API untuk autentikasi
        $token = $cashier->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'cashier' => $cashier,
                'token' => $token,
            ],
        ], 200);
    }
}
