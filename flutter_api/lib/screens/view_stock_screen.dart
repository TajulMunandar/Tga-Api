import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class ViewStockScreen extends StatefulWidget {
  const ViewStockScreen({super.key});

  @override
  _ViewStockScreenState createState() => _ViewStockScreenState();
}

class _ViewStockScreenState extends State<ViewStockScreen> {
  List<dynamic> _stocks = [];

  // Fungsi untuk mengambil data barang dan stok
  Future<void> fetchStock() async {
    try {
      final response = await http.get(Uri.parse(
          'http://localhost:8000/api/barangs')); // Ubah dengan API yang sesuai

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = json.decode(response.body);
        if (mounted) {
          setState(() {
            _stocks = data['data']; // Mengakses data stok dari response
          });
        }
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Gagal mengambil data stok!')));
      }
    } catch (e) {
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Terjadi kesalahan: $e')));
    }
  }

  // Fungsi untuk menampilkan pop-up untuk mengedit stok
  Future<void> editStockDialog(int stockId, int currentStock) async {
    final TextEditingController _stockController =
        TextEditingController(text: currentStock.toString());

    return showDialog<void>(
      context: context,
      barrierDismissible: false, // Agar dialog tidak bisa ditutup sembarangan
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Edit Stok Barang'),
          content: TextField(
            controller: _stockController,
            keyboardType: TextInputType.number,
            decoration: const InputDecoration(labelText: 'Stok Barang'),
          ),
          actions: <Widget>[
            TextButton(
              child: const Text('Batal'),
              onPressed: () {
                Navigator.of(context).pop(); // Menutup pop-up
              },
            ),
            TextButton(
              child: const Text('Simpan'),
              onPressed: () {
                // Update stok jika nilai valid
                int newStock =
                    int.tryParse(_stockController.text) ?? currentStock;
                if (newStock != currentStock) {
                  updateStock(stockId, newStock);
                }
                Navigator.of(context).pop(); // Menutup pop-up setelah simpan
              },
            ),
          ],
        );
      },
    );
  }

  // Fungsi untuk memperbarui stok barang di API
  Future<void> updateStock(int stockId, int newStock) async {
    try {
      final response = await http.patch(
        Uri.parse('http://localhost:8000/api/barangs/$stockId/stock'),
        headers: <String, String>{
          'Content-Type': 'application/json',
        },
        body: json.encode({'stock': newStock}),
      );

      if (response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Stok berhasil diperbarui!')));
        fetchStock(); // Reload data setelah update
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Gagal memperbarui stok!')));
      }
    } catch (e) {
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Terjadi kesalahan: $e')));
    }
  }

  @override
  void initState() {
    super.initState();
    fetchStock(); // Ambil data stok saat pertama kali dibuka
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Kelola Stok Barang')),
      body: _stocks.isEmpty
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: _stocks.length,
              itemBuilder: (context, index) {
                return Card(
                  elevation: 5,
                  margin:
                      const EdgeInsets.symmetric(vertical: 10, horizontal: 15),
                  child: ListTile(
                    title: Text(_stocks[index]['barang']),
                    subtitle: Text('Stok: ${_stocks[index]['stock']}'),
                    trailing: IconButton(
                      icon: const Icon(Icons.edit),
                      onPressed: () {
                        // Ketika item ditekan, tampilkan dialog edit stok
                        editStockDialog(
                            _stocks[index]['id'], _stocks[index]['stock']);
                      },
                    ),
                  ),
                );
              },
            ),
    );
  }
}
