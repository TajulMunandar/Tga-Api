<?php

namespace App\Http\Controllers;

use App\Models\Kasir;
use App\Models\Management;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            return redirect()->intended('/'); // Ganti dengan halaman setelah login
        }

        return back()->withErrors([
            'username' => 'username atau password salah.',
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:1,2,3',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role == 1) {
            Management::create([
                'id_user' => $user->id,
                'kode' => 'MGT' . $user->id,
            ]);
        } elseif ($request->role == 2) {
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

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
