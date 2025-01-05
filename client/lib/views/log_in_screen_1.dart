import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:iot_aircon_alarm_system/views/setup_ip_address_1.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/device_menu_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/login_model.dart';
import 'package:iot_aircon_alarm_system/models/api.dart';

final api = Api();
final endPoint = api.endPoint();

//Endpoint: http://IP Address:port/iot_aircon_alarm_system_backend/public/login.php
Future<Login> checkLogin(String? ipAddress, String username, String password) async {
  final String baseUrl = 'http://$ipAddress';
  final response = await http.post(
      //Uri.parse('$endPoint/login.php'),
      Uri.parse('$baseUrl$endPoint/login.php'),
      body: {
        //Correct
        'username': username,
        'password': password,
      });

  if (response.statusCode == 200) {
    print('IP: $ipAddress');
    print('BaseURL: $baseUrl$endPoint/login.php');
    return Login.fromJson(jsonDecode(response.body));
  } else {
    throw Exception('Failed to create login.');
  }
}

class LoginScreen extends StatefulWidget {
  const LoginScreen({Key? key}) : super(key: key);

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  Future<Login>? _futureLogin;

  bool isLoggedIn = false;
  int? _userId = 0;
  String? _userName = '';
  int? _groupId = 0;
  String? _ipAddress = '';

  @override
  void initState() {
    // TODO: implement initState
    super.initState();
    initIPAddress();
    print(initCheckLogIn());
  }

  Future<void> initIPAddress() async {
    final prefs = await SharedPreferences.getInstance();
    final ipAddress = prefs.getString('ipAddress');

    if (ipAddress != null) {
      setState(() {
        _ipAddress = ipAddress;
      });
    }
  }

  Future<void> initCheckLogIn() async {
    final prefs = await SharedPreferences.getInstance();
    final userId = prefs.getInt('userId');
    final userName = prefs.getString('username');
    final groupId = prefs.getInt('groupId');

    if (userId != null) {
      setState(() {
        isLoggedIn = true;
        _userId = userId;
        _userName = userName;
        _groupId = groupId;
      });
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) =>
              DeviceMenuScreen(userId: _userId),
        ),
      );

    }
  }

  Future<void> _setLoginUserId(userId) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setInt('userId', userId);
  }

  Future<void> _setLoginUserName(String username) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('username', username);
    setState(() {
      isLoggedIn = true;
    });
  }

  Future<void> _setLoginUserGroupId(userGroupId) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setInt('groupId', userGroupId);
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        resizeToAvoidBottomInset: false,
        appBar: AppBar(
          leading: const Icon(Icons.login),
          title: const Text('LOG IN'),
        ),
        body: Center(
          child: Container(
            alignment: Alignment.center,
            padding: const EdgeInsets.all(30.0),
            child: (_futureLogin == null || !isLoggedIn)
                ? buildColumnLogin()
                : buildFutureBuilderLogin(),
          ),
        ),
        bottomNavigationBar: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: <Widget>[
            Expanded(
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(padding: const EdgeInsets.only(top: 16.0, bottom: 16.0) ),
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => SetupIPAddressScreen(),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.chevron_left,
                  size: 24.0,
                ),
                label: const Text('Go to Set IP Address'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Column buildColumnLogin() {
    return Column(
      mainAxisAlignment: MainAxisAlignment.start,
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: <Widget>[
        Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: <Widget>[
            Icon(
              Icons.account_circle,
              color: Colors.white,
              size: 96.0,
            ),
            const SizedBox(height: 30.0),
            TextField(
              controller: _usernameController,
              keyboardType: TextInputType.text,
              decoration: InputDecoration(
                hintText: "Enter Username",
                labelText: "Username",
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(15),
                ),
              ),
            ),
            const SizedBox(height: 15.0),
            TextField(
              controller: _passwordController,
              keyboardType: TextInputType.text,
              decoration: InputDecoration(
                hintText: "Enter Password",
                labelText: "Password",
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(15),
                ),
              ),
              obscureText: true,
            ),
            const SizedBox(height: 30.0),
            ElevatedButton(
              onPressed: () {
                setState(() {
                  _futureLogin = checkLogin(_ipAddress, _usernameController.text, _passwordController.text);
                  _setLoginUserName(_usernameController.text);
                });
              },
              child: Text(
                'LOG IN',
                style: TextStyle(
                  fontSize: 16.0,
                ),
              ),
              style: ElevatedButton.styleFrom(
                padding: const EdgeInsets.all(21.0),
              ),
            ),
          ],
        ),
      ],
    );
  }

  FutureBuilder<Login> buildFutureBuilderLogin() {
    return FutureBuilder<Login>(
      future: _futureLogin,
      builder: (context, snapshot) {
        if (snapshot.hasData) {
          //Goto device Menu
          if(snapshot.data!.message != 'Success')
          {
            return Column(
              children: <Widget>[
                Text('${snapshot.data!.message}', style: TextStyle(color: Colors.white,)),
                const SizedBox(height: 30.0,),
                ElevatedButton.icon(
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) =>
                            LoginScreen(),
                      ),
                    );
                  },
                  icon: const Icon(
                    Icons.login,
                    size: 36.0,
                  ),
                  label: const Text('Go back to the Login page'),
                ),
              ],
            );
          }
          else if(snapshot.data!.message == 'Success' && isLoggedIn)
          {
            Future.delayed(const Duration(milliseconds: 500), () {
              _setLoginUserId(snapshot.data!.userId);
              _setLoginUserGroupId(snapshot.data!.groupId);
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) =>
                      DeviceMenuScreen(ipAddress: _ipAddress, userId: snapshot.data!.userId, userGroupId: snapshot.data!.groupId),
                ),
              );
            });
          }
        } else if (snapshot.hasError) {
          //Display Error message and add a button to go back in the Login page
          return Column(
            children: <Widget>[
              //Text('${snapshot.data!.message}', style: TextStyle(color: Colors.white,)),
              //const Text('Please make sure your web server and database server are turned ON.', style: TextStyle(color: Colors.white,)),
              //const Text('Please make sure your API Endpoint IP Address is set.', style: TextStyle(color: Colors.white,)),
              const Text('Please make sure your web server and database server are turned ON. \n\n Please make sure your API Endpoint IP Address is set.', style: TextStyle(color: Colors.white,), textAlign: TextAlign.center,),
              const SizedBox(height: 30.0,),
              ElevatedButton.icon(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) =>
                          LoginScreen(),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.login,
                  size: 36.0,
                ),
                label: const Text('Go back to the Login page'),
              ),
            ],
          );
        }
        return const CircularProgressIndicator();
      },
    );
  }
}
