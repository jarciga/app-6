import 'dart:async';
import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/log_in_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/main_menu_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/create_user_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/device_model.dart';
import 'package:iot_aircon_alarm_system/models/api.dart';

final api = Api();
final endPoint = api.endPoint();

// Multiple or collection of records
//Endpoint: http://IP Address:port/iot_aircon_alarm_system_backend/public/?req=device
Future<List<Device>> fetchDevice(String? ipAddress) async {
  final String baseUrl = 'http://$ipAddress';
  //final response = await http.get(Uri.parse('$endPoint/?req=device'));
  final response = await http.get(Uri.parse('$baseUrl$endPoint/?req=device'));
  final responseBody = response.body;
  return compute(parseDevice, responseBody);
}

// A function that converts a response body into a List<Device>.
List<Device> parseDevice(String responseBody) {
  //Check _JsonMap or List<dynamic>
  final parsedCheck = jsonDecode(responseBody);
  /*
  * Check if single or collection response.
  * If single response or _JsonMap add [] square bracket at the beginning and end of the response.
  */
  if (parsedCheck is! List<dynamic>) {
    //if parsed is _JsonMap
    print('parsed is not List<dynamic>');
    responseBody = '[$responseBody]';
  }

  final parsed = jsonDecode(responseBody) as List;
  return parsed.map((deviceData) => Device.fromJson(deviceData)).toList();
}

class DeviceMenuScreen extends StatefulWidget {
  final String? ipAddress;
  final int? userId;
  final int? userGroupId;
  final int? deviceIdSelected;
  const DeviceMenuScreen(
      {Key? key, this.ipAddress, this.userId, this.userGroupId, this.deviceIdSelected})
      : super(key: key);

  @override
  State<DeviceMenuScreen> createState() => _DeviceMenuScreenState();
}

class _DeviceMenuScreenState extends State<DeviceMenuScreen> {
  String? deviceDataValue;

  /*
  * Begin: Logout
  */
  bool isLoggedIn = false;
  int? _userId = 0;
  String? _userName = '';
  int? _groupId = 0;
  String? _ipAddress = '';

  @override
  void initState() {
    super.initState();
    initIPAddress();
    _getLoginUserName();
    _getLoginUserId();
    _getLoginGroupId();
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

  Future<void> _getLoginUserName() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _userName = prefs.getString('username');
    });
  }

  Future<void> _getLoginUserId() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _userId = prefs.getInt('userId');
    });
  }

  Future<void> _getLoginGroupId() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _groupId = prefs.getInt('groupId');
    });
  }

  Future<void> _removeLoginUserName() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.remove('username');
    setState(() {
      _userId = prefs.getInt('userId');
      isLoggedIn = false;
    });
  }

  Future<void> _removeLoginUserId() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.remove('userId');
  }

  Future<void> _removeLoginGroupId() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.remove('groupId');
  }
  /*
  * End: Logout
  */

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        resizeToAvoidBottomInset: false,
        appBar: AppBar(
          leading: const Icon(Icons.home),
          title: const Text('DEVICE MENU'),
          actions: <Widget>[
            if (_groupId == 1)
              IconButton(
                onPressed: () {
                  setState(() {
                    print('ADD USER');
                  });
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) =>
                          CreateUserScreen(ipAddress: _ipAddress, userId: widget.userId
                          ),
                    ),
                  );
                },
                tooltip: 'Add User',
                icon: const Icon(Icons.person_add),
              ),
            IconButton(
              onPressed: () {
                setState(() {
                  print('SIGN OUT');
                  _removeLoginUserName();
                  _removeLoginUserId();
                  _removeLoginGroupId();
                });
                Navigator.pushNamed(context, '/');
              },
              tooltip: 'Sign Out',
              icon: const Icon(Icons.logout),
            ),
          ],
        ),
        body: Center(
          child: Container(
            padding: const EdgeInsets.all(30.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: <Widget>[
                Icon(
                  Icons.device_hub,
                  color: Colors.white,
                  size: 96.0,
                ),
                const SizedBox(height: 30.0),
                Center(
                  child: FutureBuilder<List<Device>>(
                    //future: fetchDevice(_ipAddress),
                    future: fetchDevice(_ipAddress),
                    builder: (context, snapshot) {
                      print(snapshot.hasData);
                      if (snapshot.hasData) {
                        print(snapshot.data!.length);
                        final List<Device> deviceDataItems = snapshot.data!;
                        print(deviceDataItems);
                        return Container(
                          padding: const EdgeInsets.fromLTRB(15.0, 0.0, 15.0, 0.0),
                          decoration: const BoxDecoration(
                            color: Colors.indigo,
                          ),
                          child: DropdownButtonHideUnderline(
                            child: DropdownButton<String>(
                              dropdownColor: Colors.indigo.shade700,
                              style: const TextStyle(
                                fontSize: 16.0,
                                color: Colors.white,
                              ),
                              icon: const Icon(
                                Icons.arrow_drop_down,
                                color: Colors.white,
                              ),
                              hint: const Text('CHOOSE DEVICE', style: TextStyle(color: Colors.white,),),
                              disabledHint: const Text('CHOOSE DEVICE'),
                              value: deviceDataValue,
                              items: deviceDataItems.map((Device data) {
                                return DropdownMenuItem<String>(
                                  value: data.deviceId,
                                  child: Text(
                                    data.name.toString(),
                                    style: const TextStyle(
                                      color: Colors.white,
                                    ),
                                  ),
                                );
                              }).toList(),

                              onChanged: (String? value) {
                                setState(() {
                                  deviceDataValue = value!;
                                  Future.delayed(
                                      const Duration(milliseconds: 500), () {
                                    Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) => MainMenuScreen(
                                            ipAddress: _ipAddress,
                                            userId: widget.userId,
                                            deviceIdSelected: int.parse(value)),
                                      ),
                                    );
                                  });
                                });
                              },
                              isExpanded: true,
                            ),
                          ),
                        );
                      } else if (snapshot.hasError) {
                        //return Text('${snapshot.error}');
                        return const Text('Loading Device Menu...', style: TextStyle(color: Colors.white,));
                      }
                      // By default, show a loading spinner.
                      return const CircularProgressIndicator();
                    },
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
