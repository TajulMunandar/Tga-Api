import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class TransaksiList extends StatefulWidget {
  @override
  _TransaksiListState createState() => _TransaksiListState();
}

class Transaksi {
  final String kode;
  final String pelanggan;
  final String tarif;
  final String tanggalMasuk;
  final String tanggalSelesai;
  final int berat;
  final int status;
  final int jenis;

  Transaksi({
    required this.kode,
    required this.pelanggan,
    required this.tarif,
    required this.tanggalMasuk,
    required this.tanggalSelesai,
    required this.berat,
    required this.status,
    required this.jenis,
  });

  factory Transaksi.fromJson(Map<String, dynamic> json) {
    return Transaksi(
      kode: json['kode'],
      pelanggan: json['pelanggan']['user']['name'],
      tarif: json['tarif']['nama_layanan'], // Menyesuaikan struktur data tarif
      tanggalMasuk: json['tanggal_masuk'],
      tanggalSelesai: json['tanggal_selesai'],
      berat: json['berat'],
      status: json['status'],
      jenis: json['jenis'],
    );
  }
}

class _TransaksiListState extends State<TransaksiList> {
  late Future<List<Transaksi>> _transaksiList;

  Future<List<Transaksi>> fetchTransaksi() async {
    final response =
        await http.get(Uri.parse('http://localhost:8000/api/transaksi-list'));

    if (response.statusCode == 200) {
      List<dynamic> data = json.decode(response.body);
      return data.map((json) => Transaksi.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load transactions');
    }
  }

  Future<void> updateStatus(String kode, Transaksi transaksi) async {
    final response = await http.put(
      Uri.parse('http://localhost:8000/api/update-status/$kode'),
      body: json.encode({'status': 1}),
      headers: {'Content-Type': 'application/json'},
    );

    if (response.statusCode == 200) {
      setState(() {
        _transaksiList =
            fetchTransaksi(); // Update transaksi setelah perubahan status
      });

      var responseData = json.decode(response.body);
      double totalHarga = responseData['total_biaya'];

      // Menambahkan log untuk memeriksa apakah dialog dipanggil
      print("Total Harga: $totalHarga");

      // Menampilkan dialog dengan struk dan jumlah pembayaran
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: Text('Struk Pembayaran'),
            content: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Kode Transaksi: ${transaksi.kode}'),
                Text('Pelanggan: ${transaksi.pelanggan}'),
                Text('Tanggal Masuk: ${transaksi.tanggalMasuk}'),
                Text('Tanggal Selesai: ${transaksi.tanggalSelesai}'),
                Text('Berat: ${transaksi.berat} kg'),
                Text('Tarif: ${transaksi.tarif}'),
                Text('Status: Selesai'),
                Text(
                    'Jenis: ${transaksi.jenis == 1 ? 'Antar-Jemput' : 'Standart'}'),
                SizedBox(height: 10),
                Text('Total Pembayaran: Rp ${totalHarga.toStringAsFixed(2)}'),
              ],
            ),
            actions: <Widget>[
              TextButton(
                onPressed: () {
                  Navigator.of(context).pop();
                },
                child: Text('Tutup'),
              ),
            ],
          );
        },
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Gagal mengubah status transaksi')),
      );
    }
  }

  @override
  void initState() {
    super.initState();
    _transaksiList = fetchTransaksi();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Transaksi Laundry')),
      body: FutureBuilder<List<Transaksi>>(
        future: _transaksiList,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return Center(child: Text('Tidak ada transaksi.'));
          } else {
            var transaksis = snapshot.data!;
            return ListView.builder(
              itemCount: transaksis.length,
              itemBuilder: (context, index) {
                var transaksi = transaksis[index];
                return Card(
                  elevation: 4.0,
                  margin: const EdgeInsets.symmetric(vertical: 8.0),
                  child: InkWell(
                    onTap: () {
                      // Menampilkan dialog konfirmasi untuk mengubah status
                      showDialog(
                        context: context,
                        builder: (BuildContext context) {
                          return AlertDialog(
                            title: Text('Konfirmasi'),
                            content: Text(
                                'Apakah Anda yakin ingin mengubah status transaksi ${transaksi.pelanggan}?'),
                            actions: <Widget>[
                              TextButton(
                                onPressed: () {
                                  Navigator.of(context).pop();
                                },
                                child: Text('Batal'),
                              ),
                              TextButton(
                                onPressed: () {
                                  Navigator.of(context).pop();
                                  updateStatus(transaksi.kode, transaksi);
                                },
                                child: Text('Ya'),
                              ),
                            ],
                          );
                        },
                      );
                    },
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Kode Transaksi: ${transaksi.kode}'),
                          Text('Pelanggan: ${transaksi.pelanggan}'),
                          Text('Tarif: ${transaksi.tarif}'),
                          Text('Tanggal Masuk: ${transaksi.tanggalMasuk}'),
                          Text('Tanggal Selesai: ${transaksi.tanggalSelesai}'),
                          Text('Berat: ${transaksi.berat} kg'),
                          Text(
                              'Status: ${transaksi.status == 1 ? 'Selesai' : 'Belum Selesai'}'),
                          Text(
                              'Jenis: ${transaksi.jenis == 1 ? 'Antar-Jemput' : 'Standart'}'),
                        ],
                      ),
                    ),
                  ),
                );
              },
            );
          }
        },
      ),
    );
  }
}
