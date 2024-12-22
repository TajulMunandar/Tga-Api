import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ProfileScreen extends StatefulWidget {
  @override
  _ProfileScreenState createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _alamatController = TextEditingController();
  final TextEditingController _noHpController = TextEditingController();

  String? _name;
  String? _username;

  @override
  void initState() {
    super.initState();
    _getProfile();
  }

  // Fungsi untuk mendapatkan data profil pelanggan
  Future<void> _getProfile() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    final user_id =
        prefs.getInt('user_id') ?? 0; // Ambil userId dari SharedPreferences

    print('User ID: $user_id');

    try {
      final response = await http.get(
        Uri.parse(
            'http://localhost:8000/api/profile?user_id=$user_id'), // Kirim user_id dalam query
        headers: {
          'Content-Type': 'application/json',
        },
      );

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}'); // Debugging response body

      if (response.statusCode == 200) {
        final responseBody = json.decode(response.body);
        final data = responseBody['data'];

        setState(() {
          _name = data['name'] ?? 'No Name';
          _username = data['username'] ?? 'No Username';
          // Jika data 'alamat' atau 'no_hp' kosong, akan otomatis terisi sesuai respons dari API
          _alamatController.text = data['alamat'] ?? 'Alamat Tidak Tersedia';
          _noHpController.text = data['no_hp'] ?? 'Nomor HP Tidak Tersedia';
        });
      } else {
        final responseBody = json.decode(response.body);
        final errorMessage =
            responseBody['message'] ?? 'Gagal mengambil data profil.';
        ScaffoldMessenger.of(context)
            .showSnackBar(SnackBar(content: Text('Error: $errorMessage')));
      }
    } catch (e) {
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Terjadi kesalahan: $e')));
    }
  }

  // Fungsi untuk memperbarui data profil pelanggan
  Future<void> _updateProfile() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    final userId = prefs.getInt('user_id') ?? 0;

    if (userId == 0) {
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('User ID tidak valid')));
      return;
    }

    if (_formKey.currentState!.validate()) {
      try {
        final response = await http.patch(
          Uri.parse(
              'http://localhost:8000/api/profile/$userId/pelanggan'), // Endpoint untuk update profile
          headers: {
            'Content-Type': 'application/json',
          },
          body: json.encode({
            'alamat': _alamatController.text.isEmpty
                ? 'Alamat Tidak Tersedia'
                : _alamatController.text,
            'no_hp': _noHpController.text.isEmpty
                ? 'Nomor HP Tidak Tersedia'
                : _noHpController.text,
          }),
        );

        if (response.statusCode == 200) {
          final responseBody = json.decode(response.body);
          final successMessage = responseBody['message'] ?? 'Update berhasil';
          ScaffoldMessenger.of(context)
              .showSnackBar(SnackBar(content: Text(successMessage)));

          _getProfile();
        } else {
          final responseBody = json.decode(response.body);
          final errorMessage =
              responseBody['message'] ?? 'Gagal memperbarui data profil.';
          ScaffoldMessenger.of(context)
              .showSnackBar(SnackBar(content: Text('Error: $errorMessage')));
        }
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Terjadi kesalahan: ${e.toString()}')));
      }
    }
  }

  // Popup untuk mengedit alamat dan no_hp
  void _editProfile(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Edit Profil'),
          content: Form(
            key: _formKey, // Menambahkan form key untuk validasi
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextFormField(
                  controller: _alamatController,
                  decoration: const InputDecoration(labelText: 'Alamat'),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Alamat tidak boleh kosong';
                    }
                    return null;
                  },
                ),
                TextFormField(
                  controller: _noHpController,
                  decoration: const InputDecoration(labelText: 'No HP'),
                  keyboardType: TextInputType.phone,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Nomor HP tidak boleh kosong';
                    }
                    return null;
                  },
                ),
              ],
            ),
          ),
          actions: [
            ElevatedButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () {
                _updateProfile(); // Update data profile setelah edit
                Navigator.pop(context);
              },
              child: const Text('Simpan'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Profil Pelanggan')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            // Menampilkan data profil dalam bentuk card
            Card(
              elevation: 4,
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Nama: ${_name ?? 'No Name'}',
                      style: const TextStyle(fontSize: 18),
                    ),
                    Text(
                      'Username: ${_username ?? 'No Username'}',
                      style: const TextStyle(fontSize: 18),
                    ),
                    Text(
                      'Alamat: ${_alamatController.text.isEmpty ? 'Alamat Tidak Tersedia' : _alamatController.text}',
                      style: const TextStyle(fontSize: 18),
                    ),
                    Text(
                      'No HP: ${_noHpController.text.isEmpty ? 'Nomor HP Tidak Tersedia' : _noHpController.text}',
                      style: const TextStyle(fontSize: 18),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () => _editProfile(context),
              child: const Text('Edit Profil'),
            ),
          ],
        ),
      ),
    );
  }
}
