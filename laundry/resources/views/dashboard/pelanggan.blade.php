@extends('dashboard.layout.main')

@section('content')
    <div class="row w-100">
        <div class="col w-100">
            <div class="card w-100">
                <div class="card-body">
                    <h2>Data Pelanggan</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Kode</th>
                                <th scope="col">No Hp</th>
                                <th scope="col">Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggans as $pelanggan)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $pelanggan->User->name }}</td>
                                    <td>{{ $pelanggan->kode }}</td>
                                    <td>{{ $pelanggan->no_hp }}</td>
                                    <td>{{ $pelanggan->alamat }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
