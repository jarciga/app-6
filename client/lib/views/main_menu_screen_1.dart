import 'dart:async';
import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/setup_alarm_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/device_menu_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/sensor_data_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/current_list_alarm_history_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/humidity_list_alarm_history_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/refrigerant_list_alarm_history_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/temperature_list_alarm_history_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/vibration_list_alarm_history_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/device_model.dart';
import 'package:iot_aircon_alarm_system/models/api.dart';

final api = Api();
final endPoint = api.endPoint();

List<String> sensorItems = <String>[
  'TEMPERATURE',
  'HUMIDITY',
  'CURRENT',
  'REFRIGERANT',
  'VIBRATION'
];

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
    responseBody = '[$responseBody]';
  }

  final parsed = jsonDecode(responseBody) as List;
  return parsed.map((deviceData) => Device.fromJson(deviceData)).toList();
}

class MainMenuScreen extends StatefulWidget {
  final String? ipAddress;
  final int? deviceIdSelected;
  final int? userId;
  const MainMenuScreen(
      {Key? key, required this.ipAddress, required this.userId, required this.deviceIdSelected})
      : super(key: key);

  @override
  State<MainMenuScreen> createState() => _MainMenuScreenState();
}

class _MainMenuScreenState extends State<MainMenuScreen> {
  String? deviceDataValue;
  String? sensorDataValue;

  /*
  * Begin: Logout
  */
  bool isLoggedIn = false;
  int? _userId = 0;
  String? _userName = '';
  int? _groupId = 0;
  String? _ipAddress = '';

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

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        resizeToAvoidBottomInset: false,
        appBar: AppBar(
          leading: const Icon(Icons.home),
          title: const Text('Main Menu'),
          actions: <Widget>[
            IconButton(
              onPressed: () {
                setState(() {
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
                ElevatedButton(
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => SensorDataScreen(
                            ipAddress: _ipAddress,
                            userId: widget.userId,
                            deviceIdSelected: widget.deviceIdSelected),
                      ),
                    );
                  },
                  child: Text(
                    'SENSOR DATA',
                    style: TextStyle(
                      fontSize: 16.0,
                    ),
                  ),
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.all(21.0),
                  ),
                ),
                const SizedBox(height: 30.0),
                Container(
                  padding:  const EdgeInsets.fromLTRB(15.0, 0.0, 15.0, 0.0),
                  decoration: const BoxDecoration(
                    color: Colors.indigo,
                  ),
                  child: DropdownButtonHideUnderline(
                    child: DropdownButton<String>(
                      dropdownColor: Colors.indigo.shade700,
                      style: const TextStyle(color: Colors.white,
                          fontSize: 16.0),
                      icon: const Icon(
                        Icons.arrow_drop_down,
                        color: Colors.white,
                      ),
                      hint: const Text('ALARM HISTORY', style: TextStyle(color: Colors.white),),
                      disabledHint: const Text('ALARM HISTORY'),
                      value: sensorDataValue,
                      items: sensorItems
                          .map<DropdownMenuItem<String>>((String value) {
                        return DropdownMenuItem<String>(
                          value: value,
                          child: Text(
                            value,
                            style: const TextStyle(
                              color: Colors.white,
                            ),
                          ),
                        );
                      }).toList(),
                      onChanged: (String? value) {
                        setState(() {
                          sensorDataValue = value!;
                          if (sensorDataValue == 'TEMPERATURE') {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) =>
                                    TemperatureListAlarmHistoryScreen(
                                        ipAddress: _ipAddress,
                                        userId: widget.userId,
                                        deviceIdSelected:
                                            widget.deviceIdSelected),
                              ),
                            );
                          } else if (sensorDataValue == 'HUMIDITY') {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) =>
                                    HumidityListAlarmHistoryScreen(
                                        ipAddress: _ipAddress,
                                        userId: widget.userId,
                                        deviceIdSelected:
                                            widget.deviceIdSelected),
                              ),
                            );
                          } else if (sensorDataValue == 'CURRENT') {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) =>
                                    CurrentListAlarmHistoryScreen(
                                        ipAddress: _ipAddress,
                                        userId: widget.userId,
                                        deviceIdSelected:
                                            widget.deviceIdSelected),
                              ),
                            );
                          } else if (sensorDataValue == 'REFRIGERANT') {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) =>
                                    RefrigerantListAlarmHistoryScreen(
                                        ipAddress: _ipAddress,
                                        userId: widget.userId,
                                        deviceIdSelected:
                                            widget.deviceIdSelected),
                              ),
                            );
                          } else if (sensorDataValue == 'VIBRATION') {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) =>
                                    VibrationListAlarmHistoryScreen(
                                        ipAddress: _ipAddress,
                                        userId: widget.userId,
                                        deviceIdSelected:
                                            widget.deviceIdSelected),
                              ),
                            );
                          }
                        });
                      },
                      isExpanded: true,
                    ),
                  ),
                ),
                const SizedBox(height: 30.0),
                ElevatedButton(
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => SetupAlarmScreen(
                            ipAddress: _ipAddress,
                            userId: widget.userId,
                            deviceIdSelected: widget.deviceIdSelected),
                      ),
                    );
                  },
                  child: Text(
                    'SET ALARM',
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
                      builder: (context) => DeviceMenuScreen(
                          ipAddress: _ipAddress,
                          userId: widget.userId,
                          deviceIdSelected: widget.deviceIdSelected),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.chevron_left,
                  size: 24.0,
                ),
                label: const Text('Back to Device Menu'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
