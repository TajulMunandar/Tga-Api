import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ScreenPesanLaundry extends StatefulWidget {
  @override
  _ScreenPesanLaundryState createState() => _ScreenPesanLaundryState();
}

class _ScreenPesanLaundryState extends State<ScreenPesanLaundry> {
  List<dynamic> _barangs = [];
  List<dynamic> _tarifs = [];
  bool _isLoading = true;
  TextEditingController _kodeController = TextEditingController();
  TextEditingController _tanggalMasukController = TextEditingController();
  TextEditingController _tanggalSelesaiController = TextEditingController();
  TextEditingController _beratController = TextEditingController();
  TextEditingController _stokController = TextEditingController(); // Input stok
  int? _selectedBarangId;
  int? _selectedTarifId;
  bool _isAntarJemput = false;

  DateTime? _tanggalMasuk;
  DateTime? _tanggalSelesai;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  // Fungsi untuk mengambil data barang dan tarif
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

  // Fungsi untuk memilih tanggal
  Future<void> _selectDate(BuildContext context, bool isMasuk) async {
    DateTime initialDate = DateTime.now();
    DateTime firstDate = DateTime(2000);
    DateTime lastDate = DateTime(2101);

    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: initialDate,
      firstDate: firstDate,
      lastDate: lastDate,
    );

    if (picked != null &&
        picked != (isMasuk ? _tanggalMasuk : _tanggalSelesai)) {
      setState(() {
        if (isMasuk) {
          _tanggalMasuk = picked;
          _tanggalMasukController.text =
              "${picked.toLocal()}".split(' ')[0]; // Format date
        } else {
          _tanggalSelesai = picked;
          _tanggalSelesaiController.text =
              "${picked.toLocal()}".split(' ')[0]; // Format date
        }
      });
    }
  }

  // Fungsi untuk menyimpan transaksi
  Future<void> _saveTransaksi() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    final userId = prefs.getInt('user_id') ?? 0;

    // Validasi input sebelum mengirim request
    if (_kodeController.text.isEmpty ||
        _tanggalMasukController.text.isEmpty ||
        _tanggalSelesaiController.text.isEmpty ||
        _beratController.text.isEmpty ||
        _selectedBarangId == null ||
        _selectedTarifId == null) {
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Harap lengkapi semua input')));
      return;
    }

    final requestData = {
      'user_id': userId, // pastikan user_id dikirimkan
      'kode': _kodeController.text,
      'tanggal_masuk': _tanggalMasukController.text,
      'tanggal_selesai': _tanggalSelesaiController.text,
      'berat': int.parse(_beratController.text),
      'jenis': _isAntarJemput ? 1 : 0,
      'id_tarif': _selectedTarifId,
      'transaksi_details': [
        {
          'id_barang': _selectedBarangId,
          'stock': int.parse(_stokController.text),
        },
      ],
    };

    try {
      final response = await http.post(
        Uri.parse('http://localhost:8000/api/transaksi'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode(requestData),
      );

      if (response.statusCode == 201) {
        final data = json.decode(response.body);
        ScaffoldMessenger.of(context)
            .showSnackBar(SnackBar(content: Text(data['message'])));
      } else {
        throw Exception('Gagal menyimpan transaksi');
      }
    } catch (e) {
      print('Error: $e');
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Terjadi kesalahan')));
    }
  }

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
                    // Input Kode Transaksi
                    TextField(
                      controller: _kodeController,
                      decoration: InputDecoration(labelText: 'Kode Transaksi'),
                    ),
                    SizedBox(height: 10),
                    // Input Tanggal Masuk
                    TextField(
                      controller: _tanggalMasukController,
                      decoration: InputDecoration(labelText: 'Tanggal Masuk'),
                      readOnly: true, // Prevent manual editing
                      onTap: () => _selectDate(context, true),
                    ),
                    SizedBox(height: 10),
                    // Input Tanggal Selesai
                    TextField(
                      controller: _tanggalSelesaiController,
                      decoration: InputDecoration(labelText: 'Tanggal Selesai'),
                      readOnly: true, // Prevent manual editing
                      onTap: () => _selectDate(context, false),
                    ),
                    SizedBox(height: 10),
                    // Input Berat
                    TextField(
                      controller: _beratController,
                      decoration: InputDecoration(labelText: 'Berat (Kg)'),
                      keyboardType: TextInputType.number,
                    ),
                    SizedBox(height: 10),
                    // Dropdown untuk memilih barang
                    DropdownButton<int>(
                      hint: Text("Pilih Barang"),
                      value: _selectedBarangId,
                      onChanged: (int? newValue) {
                        setState(() {
                          _selectedBarangId = newValue;
                        });
                      },
                      items: _barangs.map<DropdownMenuItem<int>>((item) {
                        return DropdownMenuItem<int>(
                          value: item['id'],
                          child: Text(item['barang']),
                        );
                      }).toList(),
                    ),
                    SizedBox(height: 10),
                    // Input Jumlah Stok
                    TextField(
                      controller: _stokController,
                      decoration: InputDecoration(labelText: 'Jumlah Stok'),
                      keyboardType: TextInputType.number,
                    ),
                    SizedBox(height: 10),
                    // Dropdown untuk memilih tarif
                    DropdownButton<int>(
                      hint: Text("Pilih Tarif"),
                      value: _selectedTarifId,
                      onChanged: (int? newValue) {
                        setState(() {
                          _selectedTarifId = newValue;
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
                    SizedBox(height: 20),
                    // Tombol Simpan
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
