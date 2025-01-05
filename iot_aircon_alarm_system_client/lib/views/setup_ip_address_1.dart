import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:iot_aircon_alarm_system/views/log_in_screen_1.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/main_menu_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/config_setup_ip_address_model.dart';
import 'package:iot_aircon_alarm_system/models/api.dart';

class SetupIPAddressScreen extends StatefulWidget {
  const SetupIPAddressScreen({Key? key})
      : super(key: key);

  @override
  State<SetupIPAddressScreen> createState() => _SetupIPAddressScreenState();
}

class _SetupIPAddressScreenState extends State<SetupIPAddressScreen> {
  final TextEditingController _ipAddressController = TextEditingController();

  String? _ipAddress = '';

  /*_retrieveIPAddress() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      _ipAddressController.text = prefs.getString('ipAddress') ?? "";
    });
  }(*/

  @override
  void initState() {
    // TODO: implement initState
    super.initState();
    //_retrieveIPAddress();
    initIPAddress();
  }

  Future<void> initIPAddress() async {
    final prefs = await SharedPreferences.getInstance();
    final ipAddress = prefs.getString('ipAddress');

    if (ipAddress != null) {
      setState(() {
        _ipAddress = ipAddress;
        _ipAddressController.text = ipAddress;
      });
      /*Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) =>
              LoginScreen(),
        ),
      );*/
    }
  }

  /*
  @override
  void dispose() {
    // TODO: implement dispose
    _ipAddressController.dispose();
    super.dispose();
  }
  */

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        resizeToAvoidBottomInset: false,
        appBar: AppBar(
          leading: const Icon(Icons.app_settings_alt),
          title: const Text('Set IP Address'),

        ),
        body: Center(
          child: Container(
            alignment: Alignment.center,
            padding: const EdgeInsets.all(30.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: <Widget>[
                Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: <Widget>[
                    Icon(
                      Icons.app_settings_alt,
                      color: Colors.white,
                      size: 96.0,
                    ),
                    const SizedBox(height: 30.0),
                    TextField(
                      controller: _ipAddressController,
                      keyboardType: TextInputType.name,
                      decoration: InputDecoration(
                        hintText: "Enter IP Address",
                        labelText: "IP Address",
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(15),
                        ),
                      ),
                    ),
                    //SizedBox(height: 15.0),
                    const SizedBox(height: 30.0),
                    ElevatedButton(
                      onPressed: () async {
                        SharedPreferences prefs = await SharedPreferences.getInstance();
                        prefs.setString('ipAddress', _ipAddressController.text);
                      },
                      child: Text(
                        'SAVE IP ADDRESS',
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
            ),
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
                      builder: (context) => LoginScreen(),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.chevron_left,
                  size: 24.0,
                ),
                label: const Text('Go back to Login'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
