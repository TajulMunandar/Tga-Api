<header>
    <h1>Dashboard Manajemen Laundry Namo</h1>
</header>

<nav>
    <div class="row justify-content-center align-items-center">
        <div class="col col-lg-10 d-flex justify-content-start">
            <a href="/">Dashboard</a>
            <a href="{{ route('pelanggan.index') }}">Data Pelanggan</a>
            <a href="{{ route('kasir.index') }}">Data Kasir</a>
            <a href="{{ route('barang.index') }}">Manajemen Stok</a>
            <a href="{{ route('tarif.index') }}">Tarif Laundry</a>
            <a href="{{ route('transaksi.index') }}">Transaksi</a>
            <a href="{{ route('laporan') }}">Laporan Transaksi</a>
        </div>
        <div class="col col-lg-2 d-flex align-items-center">
            <a href="{{ route('profile.index') }}" style="color:white" class="me-3">{{ auth()->user()->username }}</a>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="btn p-1 btn-danger">logout</button>
            </form>
        </div>
    </div>
</nav>
