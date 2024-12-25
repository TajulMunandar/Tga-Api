import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:laundry_app/screens/screen_struk_pembayaran.dart';

class ScreenPesanLaundry extends StatefulWidget {
  @override
  _ScreenPesanLaundryState createState() => _ScreenPesanLaundryState();
}

class _ScreenPesanLaundryState extends State<ScreenPesanLaundry> {
  // Data & State Management
  List<dynamic> _barangs = [];
  List<dynamic> _tarifs = [];
  bool _isLoading = true;

  // Controllers & FocusNodes
  final TextEditingController _kodeController = TextEditingController();
  final TextEditingController _tanggalMasukController = TextEditingController();
  final TextEditingController _tanggalSelesaiController =
      TextEditingController();
  final TextEditingController _beratController = TextEditingController();

  final FocusNode _kodeFocusNode = FocusNode();
  final FocusNode _beratFocusNode = FocusNode();

  // Transaksi Details & Selection
  List<Map<String, dynamic>> _transaksiDetails = [];
  int? _selectedTarifId;
  bool _isAntarJemput = false;
  double _totalBayar = 0.0;

  DateTime? _tanggalMasuk;
  DateTime? _tanggalSelesai;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  @override
  void dispose() {
    _kodeController.dispose();
    _tanggalMasukController.dispose();
    _tanggalSelesaiController.dispose();
    _beratController.dispose();
    _kodeFocusNode.dispose();
    _beratFocusNode.dispose();
    super.dispose();
  }

  // Fetch Data
  Future<void> _fetchData() async {
    try {
      final response =
          await http.get(Uri.parse('http://localhost:8000/api/transaksiData'));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          _barangs = data['barangs'];
          _tarifs = data['tarifs'];
          _isLoading = false;
        });
      } else {
        throw Exception('Gagal mengambil data');
      }
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      print('Error: $e');
    }
  }

  // Date Picker Handler
  Future<void> _selectDate(BuildContext context, bool isMasuk) async {
    FocusScope.of(context)
        .unfocus(); // Unfocus input before showing date picker

    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime(2101),
    );

    if (picked != null) {
      setState(() {
        if (isMasuk) {
          _tanggalMasuk = picked;
          _tanggalMasukController.text = "${picked.toLocal()}".split(' ')[0];
        } else {
          _tanggalSelesai = picked;
          _tanggalSelesaiController.text = "${picked.toLocal()}".split(' ')[0];
        }
      });
    }
  }

  // Add Detail Barang
  void _addTransaksiDetail() {
    setState(() {
      _transaksiDetails.add({'id_barang': null, 'stock': null});
    });
  }

  // Calculate Total Biaya
  void _calculateTotalBayar() {
    double total = 0.0;

    // Tambahkan tarif
    if (_selectedTarifId != null) {
      final selectedTarif =
          _tarifs.firstWhere((tarif) => tarif['id'] == _selectedTarifId);
      total += selectedTarif['tarif'] ?? 0.0;
    }

    // Tambahkan harga barang
    for (var detail in _transaksiDetails) {
      if (detail['id_barang'] != null && detail['stock'] != null) {
        final selectedBarang = _barangs
            .firstWhere((barang) => barang['id'] == detail['id_barang']);
        final hargaBarang = selectedBarang['harga'] ?? 0.0;
        total += hargaBarang * (detail['stock'] ?? 0);
      }
    }

    setState(() {
      _totalBayar = total;
    });
  }

  // Save Transaksi
  Future<void> _saveTransaksi() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    final userId = prefs.getInt('user_id') ?? 0;

    if (_kodeController.text.isEmpty ||
        _tanggalMasukController.text.isEmpty ||
        _tanggalSelesaiController.text.isEmpty ||
        _beratController.text.isEmpty ||
        _selectedTarifId == null ||
        _transaksiDetails.isEmpty) {
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Harap lengkapi semua input')));
      return;
    }

    final requestData = {
      'user_id': userId,
      'kode': _kodeController.text,
      'tanggal_masuk': _tanggalMasukController.text,
      'tanggal_selesai': _tanggalSelesaiController.text,
      'berat': int.parse(_beratController.text),
      'jenis': _isAntarJemput ? 1 : 0,
      'id_tarif': _selectedTarifId,
      'transaksi_details': _transaksiDetails,
    };

    try {
      final response = await http.post(
        Uri.parse('http://localhost:8000/api/transaksi'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode(requestData),
      );

      if (response.statusCode == 201) {
        final data = json.decode(response.body);
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => ScreenStrukPembayaran(
              kodeTransaksi: _kodeController.text,
              tanggalMasuk: _tanggalMasukController.text,
              tanggalSelesai: _tanggalSelesaiController.text,
              totalBayar: _totalBayar,
              detailBarang: _transaksiDetails.map((detail) {
                final selectedBarang = _barangs.firstWhere(
                    (barang) => barang['id'] == detail['id_barang']);
                return {
                  'nama_barang': selectedBarang['barang'],
                  'stock': detail['stock'],
                  'harga': selectedBarang['harga'],
                };
              }).toList(),
            ),
          ),
        );
      } else {
        print('Response body: ${response.body}');
        throw Exception('Gagal menyimpan transaksi');
      }
    } catch (e) {
      print('Error: $e');
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Terjadi kesalahan')));
    }
  }

  // Build UI
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Form Pesan Laundry'),
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: SingleChildScrollView(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    TextField(
                      controller: _kodeController,
                      focusNode: _kodeFocusNode,
                      decoration: InputDecoration(labelText: 'Kode Transaksi'),
                    ),
                    SizedBox(height: 10),
                    TextField(
                      controller: _tanggalMasukController,
                      decoration: InputDecoration(labelText: 'Tanggal Masuk'),
                      readOnly: true,
                      onTap: () => _selectDate(context, true),
                    ),
                    SizedBox(height: 10),
                    TextField(
                      controller: _tanggalSelesaiController,
                      decoration: InputDecoration(labelText: 'Tanggal Selesai'),
                      readOnly: true,
                      onTap: () => _selectDate(context, false),
                    ),
                    SizedBox(height: 10),
                    TextField(
                      controller: _beratController,
                      focusNode: _beratFocusNode,
                      decoration: InputDecoration(labelText: 'Berat (Kg)'),
                      keyboardType: TextInputType.number,
                    ),
                    SizedBox(height: 10),
                    DropdownButton<int>(
                      hint: Text("Pilih Tarif"),
                      value: _selectedTarifId,
                      onChanged: (int? newValue) {
                        setState(() {
                          _selectedTarifId = newValue;
                          _calculateTotalBayar();
                        });
                      },
                      items: _tarifs.map<DropdownMenuItem<int>>((item) {
                        return DropdownMenuItem<int>(
                          value: item['id'],
                          child: Text(item['nama_layanan']),
                        );
                      }).toList(),
                    ),
                    SizedBox(height: 10),
                    CheckboxListTile(
                      title: Text('Jenis Layanan: Antar Jemput'),
                      value: _isAntarJemput,
                      onChanged: (bool? value) {
                        setState(() {
                          _isAntarJemput = value!;
                        });
                      },
                    ),
                    SizedBox(height: 10),
                    Text('Detail Barang',
                        style: TextStyle(
                            fontSize: 16, fontWeight: FontWeight.bold)),
                    SizedBox(height: 10),
                    ListView.builder(
                      shrinkWrap: true,
                      itemCount: _transaksiDetails.length,
                      itemBuilder: (context, index) {
                        final selectedBarangId =
                            _transaksiDetails[index]['id_barang'];
                        final selectedBarang = selectedBarangId != null
                            ? _barangs.firstWhere(
                                (barang) => barang['id'] == selectedBarangId,
                                orElse: () => null)
                            : null;

                        final maxStock = selectedBarang != null
                            ? selectedBarang['stock']
                            : 0;

                        return Column(
                          children: [
                            DropdownButton<int>(
                              hint: Text("Pilih Barang"),
                              value: _transaksiDetails[index]['id_barang'],
                              onChanged: (int? newValue) {
                                setState(() {
                                  _transaksiDetails[index]['id_barang'] =
                                      newValue;
                                  _calculateTotalBayar();
                                });
                              },
                              items:
                                  _barangs.map<DropdownMenuItem<int>>((item) {
                                return DropdownMenuItem<int>(
                                  value: item['id'],
                                  child: Text(item['barang']),
                                );
                              }).toList(),
                            ),
                            TextField(
                              decoration: InputDecoration(
                                labelText: 'Jumlah Stok',
                                errorText: (selectedBarangId != null &&
                                        _transaksiDetails[index]['stock'] !=
                                            null &&
                                        _transaksiDetails[index]['stock'] >
                                            maxStock)
                                    ? 'Stok tidak mencukupi'
                                    : null,
                              ),
                              keyboardType: TextInputType.number,
                              onChanged: (value) {
                                final inputStock = int.tryParse(value);
                                setState(() {
                                  if (inputStock != null &&
                                      inputStock <= maxStock) {
                                    _transaksiDetails[index]['stock'] =
                                        inputStock;
                                  } else {
                                    _transaksiDetails[index]['stock'] =
                                        maxStock;
                                  }
                                  _calculateTotalBayar();
                                });
                              },
                            ),
                            Divider(),
                          ],
                        );
                      },
                    ),
                    ElevatedButton(
                      onPressed: _addTransaksiDetail,
                      child: Text('Tambah Barang'),
                    ),
                    SizedBox(height: 20),
                    Text(
                      'Total Bayar: Rp $_totalBayar',
                      style:
                          TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    SizedBox(height: 20),
                    ElevatedButton(
                      onPressed: _saveTransaksi,
                      child: Text('Simpan Transaksi'),
                    ),
                  ],
                ),
              ),
            ),
    );
  }
}
