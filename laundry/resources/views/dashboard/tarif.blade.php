@extends('dashboard.layout.main')

@section('content')
    <div class="row w-100">
        <div class="col w-100">
            <div class="card w-100">
                <h2>Data Tarif</h2>
                <div class="card-body">
                    <button type="button" class="btn btn-primary float-start mb-2" data-bs-toggle="modal"
                        data-bs-target="#tambah">
                        Tambah
                    </button>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode</th>
                                <th scope="col">Nama Layanan</th>
                                <th scope="col">Tarif</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tarifs as $tarif)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $tarif->kode }}</td>
                                    <td>{{ $tarif->nama_layanan }}</td>
                                    <td>{{ $tarif->tarif }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $tarif->id }}">
                                            Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <form action="{{ route('tarif.destroy', $tarif->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $tarif->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $tarif->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $tarif->id }}">Edit Tarif
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('tarif.update', $tarif->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="kode" class="form-label">Kode Tarif</label>
                                                        <input type="text" class="form-control" id="kode"
                                                            name="kode" value="{{ $tarif->kode }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama_layanan" class="form-label">Nama Layanan</label>
                                                        <input type="text" class="form-control" id="nama_layanan"
                                                            name="nama_layanan" value="{{ $tarif->nama_layanan }}"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tarif" class="form-label">Tarif</label>
                                                        <input type="number" class="form-control" id="tarif"
                                                            name="tarif" value="{{ $tarif->tarif }}" required>
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
                    <h5 class="modal-title" id="tambahLabel">Tambah Tarif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('tarif.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Tarif</label>
                            <input type="text" class="form-control" id="kode" name="kode" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_layanan" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" id="nama_layanan" name="nama_layanan" required>
                        </div>
                        <div class="mb-3">
                            <label for="tarif" class="form-label">Tarif</label>
                            <input type="number" class="form-control" id="tarif" name="tarif" required>
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
