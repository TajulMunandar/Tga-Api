<?php

namespace App\Http\Controllers;

use App\Models\Kasir;
use App\Models\Management;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Cek role dan ambil data profil sesuai role
        if ($user->role == 1) {
            $profile = Management::where('id_user', $user->id)->with('User')->first();
        } elseif ($user->role == 2) {
            $profile = Kasir::where('id_user', $user->id)->with('User')->first();
        } elseif ($user->role == 3) {
            $profile = Pelanggan::where('id_user', $user->id)->with('User')->first();
        } else {
            abort(403, 'Role tidak dikenal.');
        }

        return view('dashboard.profile', compact('profile', 'user'));
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
        ]);

        $profile = null;

        if ($request->user()->role == 1) {
            $profile = Management::where('id_user', $request->user()->id)->first();
        } elseif ($request->user()->role == 2) {
            $profile = Kasir::where('id_user', $request->user()->id)->first();
        } elseif ($request->user()->role == 3) {
            $profile = Pelanggan::where('id_user', $request->user()->id)->first();
        }

        $profile->alamat = $request->input('alamat');
        $profile->no_hp = $request->input('no_hp');

        $profile->save();

        return redirect()->route('profile.index', $profile->id)
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
