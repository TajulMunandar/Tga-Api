@extends('dashboard.layout.main')

@section('content')
    <div class="row w-100">
        <div class="col w-100">
            <div class="card w-100">
                <div class="card-body">
                    <h2>Data Laporan</h2>
                    <table class="table overflow-auto">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">Jenis Laundry</th>
                                <th scope="col">Tanggal Masuk</th>
                                <th scope="col">Tanggal Selesai</th>
                                <th scope="col">Berat</th>
                                <th scope="col">Status</th>
                                <th scope="col">Jenis</th>
                                <th scope="col">Barang</th>
                                <th scope="col">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporans as $laporan)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $laporan->kode }}</td>
                                    <td>{{ $laporan->Pelanggan->User->name }}</td>
                                    <td>{{ $laporan->Tarif->nama_layanan }}</td>
                                    <td>{{ $laporan->tanggal_masuk }}</td>
                                    <td>{{ $laporan->tanggal_selesai }}</td>
                                    <td>{{ $laporan->berat }}</td>
                                    <td>
                                        @if ($laporan->status == 1)
                                            Selesai
                                        @else
                                            Proses
                                        @endif
                                    </td>
                                    <td>
                                        @if ($laporan->jenis == 1)
                                            Antar Jemput
                                        @else
                                            Manual
                                        @endif
                                    </td>
                                    <td>
                                        @foreach ($laporan->TransaksiDetail as $detail)
                                            <div>{{ $detail->Barang->barang }}</div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($laporan->TransaksiDetail as $detail)
                                            <div>{{ $detail->Barang->stock }}</div>
                                        @endforeach
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
