@extends('dashboard.layout.main')

@section('content')
    <div class="row w-100">
        <div class="col w-100">
            <div class="card w-100">
                <div class="card-body">
                    <h2>Edit Transaksi</h2>
                    <form method="POST" action="{{ route('transaksi.update', $transaksi->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode</label>
                            <input type="text" class="form-control" id="kode" name="kode"
                                value="{{ $transaksi->kode }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_pelanggan" class="form-label">Pelanggan</label>
                            <select class="form-select" id="id_pelanggan" name="id_pelanggan" required>
                                @foreach ($pelanggans as $pelanggan)
                                    <option value="{{ $pelanggan->id }}"
                                        {{ $transaksi->id_pelanggan == $pelanggan->id ? 'selected' : '' }}>
                                        {{ $pelanggan->User->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_tarif" class="form-label">Layanan</label>
                            <select class="form-select" id="id_tarif" name="id_tarif" required>
                                @foreach ($tarifs as $tarif)
                                    <option value="{{ $tarif->id }}"
                                        {{ $transaksi->id_tarif == $tarif->id ? 'selected' : '' }}>
                                        {{ $tarif->nama_layanan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk"
                                value="{{ $transaksi->tanggal_masuk }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                                value="{{ $transaksi->tanggal_selesai }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="berat" class="form-label">Berat (kg)</label>
                            <input type="number" class="form-control" id="berat" name="berat"
                                value="{{ $transaksi->berat }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="0" {{ $transaksi->status == 0 ? 'selected' : '' }}>Proses</option>
                                <option value="1" {{ $transaksi->status == 1 ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis</label>
                            <select class="form-select" id="jenis" name="jenis" required>
                                <option value="0" {{ $transaksi->jenis == 0 ? 'selected' : '' }}>Manual</option>
                                <option value="1" {{ $transaksi->jenis == 1 ? 'selected' : '' }}>Antar Jemput</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
