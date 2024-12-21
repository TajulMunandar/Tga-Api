@extends('dashboard.layout.main')

@section('content')
    <h2 class="mb-4">Profil Pengguna</h2>
    <div class="card w-100">
        <div class="card-body ">
            <form method="POST" action="{{ route('profile.update', $profile->id) }}">
                @csrf
                @method('PUT')

                <h5 class="card-title">Nama: {{ $user->name }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Username: {{ $user->username }}</h6>
                <span class="card-text">Role:
                    @if ($user->role == 1)
                        Management
                    @elseif ($user->role == 2)
                        Kasir
                    @elseif ($user->role == 3)
                        Pelanggan
                    @endif
                </span>

                <hr>
                <h5>Detail Profil</h5>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                        value="{{ $profile->alamat ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">No. Telepon</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp"
                        value="{{ $profile->no_hp ?? '' }}">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
