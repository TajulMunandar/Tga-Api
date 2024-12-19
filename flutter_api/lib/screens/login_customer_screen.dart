import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class LoginCustomerScreen extends StatefulWidget {
  const LoginCustomerScreen({super.key});

  @override
  _LoginCustomerScreenState createState() => _LoginCustomerScreenState();
}

class _LoginCustomerScreenState extends State<LoginCustomerScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  Future<void> loginCustomer() async {
    if (_formKey.currentState!.validate()) {
      final response = await http.post(
        Uri.parse('http://127.0.0.1:8000//api/auth/login/customer'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({
          'email': _emailController.text,
          'password': _passwordController.text,
        }),
      );

      if (response.statusCode == 200) {
        // Success
        ScaffoldMessenger.of(context)
            .showSnackBar(SnackBar(content: Text('Login pelanggan berhasil!')));
        // Lakukan navigasi atau aksi setelah login berhasil
      } else {
        // Error
        ScaffoldMessenger.of(context)
            .showSnackBar(SnackBar(content: Text('Gagal login pelanggan!')));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login Pelanggan')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: _emailController,
                decoration: InputDecoration(labelText: 'Email'),
                validator: (value) {
                  if (value == null || value.isEmpty || !value.contains('@')) {
                    return 'Email tidak valid';
                  }
                  return null;
                },
              ),
              TextFormField(
                controller: _passwordController,
                decoration: InputDecoration(labelText: 'Password'),
                obscureText: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Password harus diisi';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20),
              ElevatedButton(
                onPressed: loginCustomer,
                child: Text('Login Pelanggan'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
