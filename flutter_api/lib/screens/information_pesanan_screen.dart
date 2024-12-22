import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class InformasiPesananScreen extends StatefulWidget {
  @override
  _InformasiPesananScreenState createState() => _InformasiPesananScreenState();
}

class _InformasiPesananScreenState extends State<InformasiPesananScreen> {
  Map<String, dynamic>? _pesanan; // Menggunakan Map untuk satu data
  bool _isLoading = true;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _fetchPesanan();
  }

  // Fungsi untuk mengambil data pesanan terbaru dari API
  // Fungsi untuk mengambil data pesanan dari API
  Future<void> _fetchPesanan() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      final userId = prefs.getInt('user_id') ?? 0;

      if (userId == 0) {
        throw Exception('User ID tidak ditemukan. Silakan login kembali.');
      }

      final response = await http.get(
        Uri.parse('http://localhost:8000/api/transaksi?user_id=$userId'),
        headers: {
          'Content-Type': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);

        // Konversi data yang diperlukan dari int ke bool
        setState(() {
          if (data['data'] != null) {
            _pesanan = {
              'kode': data['data']['kode'],
              'tanggal_masuk': data['data']['tanggal_masuk'],
              'tanggal_selesai': data['data']['tanggal_selesai'],
              'berat': data['data']['berat'],
              'jenis': data['data']['jenis'] == 1, // Konversi int ke bool
              'status': data['data']['status'] == 1, // Konversi int ke bool
            };
          } else {
            _pesanan = null;
          }
          _isLoading = false;
        });
      } else {
        final data = json.decode(response.body);
        throw Exception(data['message'] ?? 'Gagal mengambil data pesanan.');
      }
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
        _isLoading = false;
      });
    }
  }

  // Fungsi untuk menampilkan status pesanan
  String _getStatus(bool status) {
    return status ? 'Selesai' : 'Dalam Proses';
  }

  // Fungsi untuk menampilkan jenis layanan
  String _getJenis(bool jenis) {
    return jenis ? 'Antar Jemput' : 'Regular';
  }

  @override
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Informasi Pesanan'),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage != null
              ? Center(
                  child: Text(
                    _errorMessage!,
                    style: const TextStyle(color: Colors.red, fontSize: 16),
                    textAlign: TextAlign.center,
                  ),
                )
              : _pesanan == null
                  ? const Center(
                      child: Text(
                        'Tidak ada pesanan.',
                        style: TextStyle(fontSize: 16),
                      ),
                    )
                  : Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Card(
                        elevation: 4,
                        child: Padding(
                          padding: const EdgeInsets.all(16.0),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  // Bagian label
                                  Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      const Text(
                                        'Kode Pesanan:',
                                        style: TextStyle(
                                          fontSize: 16,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                      const SizedBox(height: 8),
                                      const Text(
                                        'Tanggal Masuk:',
                                        style: TextStyle(fontSize: 16),
                                      ),
                                      const SizedBox(height: 8),
                                      const Text(
                                        'Tanggal Selesai:',
                                        style: TextStyle(fontSize: 16),
                                      ),
                                      const SizedBox(height: 8),
                                      const Text(
                                        'Berat:',
                                        style: TextStyle(fontSize: 16),
                                      ),
                                      const SizedBox(height: 8),
                                      const Text(
                                        'Jenis:',
                                        style: TextStyle(fontSize: 16),
                                      ),
                                      const SizedBox(height: 8),
                                      const Text(
                                        'Status:',
                                        style: TextStyle(fontSize: 16),
                                      ),
                                    ],
                                  ),
                                  const SizedBox(width: 16), // Spasi horizontal
                                  // Bagian isi
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          _pesanan!['kode'],
                                          style: const TextStyle(
                                            fontSize: 16,
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                        const SizedBox(height: 8),
                                        Text(
                                          _pesanan!['tanggal_masuk'],
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        const SizedBox(height: 8),
                                        Text(
                                          _pesanan!['tanggal_selesai'],
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        const SizedBox(height: 8),
                                        Text(
                                          '${_pesanan!['berat']} Kg',
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        const SizedBox(height: 8),
                                        Text(
                                          _getJenis(_pesanan!['jenis']),
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        const SizedBox(height: 8),
                                        Text(
                                          _getStatus(_pesanan!['status']),
                                          style: TextStyle(
                                            fontSize: 16,
                                            color: _pesanan!['status']
                                                ? Colors.green
                                                : Colors.orange,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 16),
                              // Pesan jika status selesai
                              if (_pesanan!['status'])
                                const Text(
                                  'Segera ambil laundry Anda!',
                                  style: TextStyle(
                                    fontSize: 16,
                                    color: Colors.red,
                                    fontWeight: FontWeight.bold,
                                  ),
                                  textAlign: TextAlign.center,
                                ),
                            ],
                          ),
                        ),
                      ),
                    ),
    );
  }
}
