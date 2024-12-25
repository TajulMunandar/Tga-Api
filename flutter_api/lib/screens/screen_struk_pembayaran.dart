import 'package:flutter/material.dart';

class ScreenStrukPembayaran extends StatelessWidget {
  final String kodeTransaksi;
  final String tanggalMasuk;
  final String tanggalSelesai;
  final double totalBayar;
  final List<Map<String, dynamic>> detailBarang;

  ScreenStrukPembayaran({
    required this.kodeTransaksi,
    required this.tanggalMasuk,
    required this.tanggalSelesai,
    required this.totalBayar,
    required this.detailBarang,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Struk Pembayaran'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Kode Transaksi: $kodeTransaksi',
                style: TextStyle(fontSize: 16)),
            Text('Tanggal Masuk: $tanggalMasuk',
                style: TextStyle(fontSize: 16)),
            Text('Tanggal Selesai: $tanggalSelesai',
                style: TextStyle(fontSize: 16)),
            Divider(),
            Text('Detail Barang:',
                style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
            ...detailBarang.map((barang) => ListTile(
                  title: Text('${barang['nama_barang']}'),
                  subtitle: Text(
                      'Jumlah: ${barang['stock']} x Harga: Rp ${barang['harga']}'),
                  trailing: Text('Rp ${barang['stock'] * barang['harga']}'),
                )),
            Divider(),
            Text(
              'Total Bayar: Rp $totalBayar',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
          ],
        ),
      ),
    );
  }
}
