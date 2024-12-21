@extends('dashboard.layout.main')

@section('content')
    <div class="row w-100">
        <div class="col w-100">
            <div class="card w-100">
                <h2>Data Barang</h2>
                <div class="card-body">
                    <button type="button" class="btn btn-primary float-start mb-2" data-bs-toggle="modal"
                        data-bs-target="#tambah">
                        Tambah
                    </button>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Kode</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $barang)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $barang->barang }}</td>
                                    <td>{{ $barang->kode }}</td>
                                    <td>{{ $barang->stock }}</td>
                                    <td>{{ $barang->harga }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $barang->id }}">
                                            Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $barang->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $barang->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $barang->id }}">Edit Barang
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('barang.update', $barang->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="kode" class="form-label">Kode Barang</label>
                                                        <input type="text" class="form-control" id="kode"
                                                            name="kode" value="{{ $barang->kode }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="barang" class="form-label">Nama Barang</label>
                                                        <input type="text" class="form-control" id="barang"
                                                            name="barang" value="{{ $barang->barang }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="stock" class="form-label">Stok</label>
                                                        <input type="number" class="form-control" id="stock"
                                                            name="stock" value="{{ $barang->stock }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga" class="form-label">Harga</label>
                                                        <input type="number" class="form-control" id="harga"
                                                            name="harga" value="{{ $barang->harga }}" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Modal -->
    <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="tambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('barang.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Barang</label>
                            <input type="text" class="form-control" id="kode" name="kode" required>
                        </div>
                        <div class="mb-3">
                            <label for="barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="barang" name="barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
