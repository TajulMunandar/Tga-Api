import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class VerifyOrdersScreen extends StatefulWidget {
  const VerifyOrdersScreen({super.key});

  @override
  _VerifyOrdersScreenState createState() => _VerifyOrdersScreenState();
}

class _VerifyOrdersScreenState extends State<VerifyOrdersScreen> {
  List<dynamic> _orders = [];

  // Fungsi untuk mengambil data pesanan
  Future<void> fetchOrders() async {
    final response = await http
        .get(Uri.parse('http://127.0.0.1:8000/api/management/transactions'));

    if (response.statusCode == 200) {
      setState(() {
        _orders = json.decode(response.body);
      });
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal mengambil data pesanan!')));
    }
  }

  // Fungsi untuk memverifikasi transaksi
  Future<void> verifyTransaction(int transactionId) async {
    final response = await http.post(
      Uri.parse('http://127.0.0.1:8000/api/verify_transaction'),
      body: json.encode({'transaction_id': transactionId}),
      headers: {'Content-Type': 'application/json'},
    );

    if (response.statusCode == 200) {
      ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Transaksi berhasil diverifikasi!')));
      fetchOrders(); // Refresh transaksi setelah diverifikasi
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal memverifikasi transaksi!')));
    }
  }

  @override
  void initState() {
    super.initState();
    fetchOrders(); // Ambil data pesanan saat halaman pertama kali dimuat
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Verifikasi Pesanan')),
      body: ListView.builder(
        itemCount: _orders.length,
        itemBuilder: (context, index) {
          return ListTile(
            title: Text('Pesanan ${_orders[index]['id']}'),
            subtitle: Text(
                'Antar jemput: ${_orders[index]['pickup'] ? 'Ya' : 'Tidak'}'),
            trailing: IconButton(
              icon: Icon(Icons.check),
              onPressed: () {
                verifyTransaction(
                    _orders[index]['id']); // Verifikasi pesanan berdasarkan ID
              },
            ),
          );
        },
      ),
    );
  }
}
