import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  _RegisterScreenState createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _passwordConfirmationController =
      TextEditingController();

  String? _selectedRole; // Role yang dipilih
  final List<Map<String, dynamic>> _roles = [
    {'value': 2, 'label': 'Kasir'},
    {'value': 3, 'label': 'Pelanggan'},
  ];

  /// Simulasi validasi role menggunakan Future.value
  Future<bool> isRoleValid() {
    if (_selectedRole == null) {
      return Future.value(false);
    }
    return Future.value(true);
  }

  /// Simulasi respons API untuk testing lokal
  Future<void> register() async {
    if (_formKey.currentState!.validate()) {
      if (!await isRoleValid()) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Role belum dipilih!')),
        );
        return;
      }

      final payload = {
        'name': _nameController.text,
        'username': _usernameController.text,
        'password': _passwordController.text,
        'password_confirmation': _passwordConfirmationController.text,
        'role': _selectedRole,
      };

      try {
        final response = await http.post(
          Uri.parse('http://127.0.0.1:8000/api/registerApi'),
          headers: {'Content-Type': 'application/json'},
          body: json.encode(payload),
        );

        print('Status Code: ${response.statusCode}');
        print('Response Body: ${response.body}');

        if (response.statusCode == 201) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Pengguna berhasil didaftarkan!')),
          );
          Navigator.pop(context); // Kembali ke halaman sebelumnya
        } else {
          final responseBody = json.decode(response.body);
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
                content: Text(
                    responseBody['message'] ?? 'Gagal mendaftar pengguna!')),
          );
        }
      } catch (e) {
        print('Error: $e');
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Terjadi kesalahan jaringan!')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Register')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: SingleChildScrollView(
            child: Column(
              children: [
                TextFormField(
                  controller: _nameController,
                  decoration: const InputDecoration(labelText: 'Nama'),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Nama harus diisi';
                    }
                    return null;
                  },
                ),
                TextFormField(
                  controller: _usernameController,
                  decoration: const InputDecoration(labelText: 'Username'),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Username harus diisi';
                    }
                    return null;
                  },
                ),
                TextFormField(
                  controller: _passwordController,
                  decoration: const InputDecoration(labelText: 'Password'),
                  obscureText: true,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Password harus diisi';
                    }
                    return null;
                  },
                ),
                TextFormField(
                  controller: _passwordConfirmationController,
                  decoration:
                      const InputDecoration(labelText: 'Konfirmasi Password'),
                  obscureText: true,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Konfirmasi password harus diisi';
                    }
                    if (value != _passwordController.text) {
                      return 'Password tidak cocok';
                    }
                    return null;
                  },
                ),
                DropdownButtonFormField<String>(
                  value: _selectedRole,
                  decoration: const InputDecoration(labelText: 'Pilih Role'),
                  items: _roles
                      .map((role) => DropdownMenuItem<String>(
                            value: role['value'].toString(),
                            child: Text(role['label']),
                          ))
                      .toList(),
                  onChanged: (value) {
                    setState(() {
                      _selectedRole = value;
                    });
                  },
                  validator: (value) {
                    if (value == null) {
                      return 'Role harus dipilih';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 20),
                ElevatedButton(
                  onPressed: register,
                  child: const Text('Daftar Pengguna'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
