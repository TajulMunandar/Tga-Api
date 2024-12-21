@extends('dashboard.layout.main')

@section('content')
    <div class="row w-100">
        <div class="col w-100">
            <div class="card w-100">
                <div class="card-body">
                    <h2>Data Transaksi</h2>
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Selesai</th>
                                <th>Berat</th>
                                <th>Status</th>
                                <th>Jenis</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $transaksi)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaksi->kode }}</td>
                                    <td>{{ $transaksi->Pelanggan->User->name }}</td>
                                    <td>{{ $transaksi->Tarif->nama_layanan }}</td>
                                    <td>{{ $transaksi->tanggal_masuk }}</td>
                                    <td>{{ $transaksi->tanggal_selesai }}</td>
                                    <td>{{ $transaksi->berat }} kg</td>
                                    <td>{{ $transaksi->status ? 'Selesai' : 'Proses' }}</td>
                                    <td>{{ $transaksi->jenis ? 'Antar Jemput' : 'Manual' }}</td>
                                    <td>
                                        <a href="{{ route('transaksi.edit', $transaksi->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
