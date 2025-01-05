import 'dart:async';
import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/main_menu_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/sensor_data_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/vibration_list_model.dart';
import 'package:iot_aircon_alarm_system/models/api.dart';

final api = Api();
final endPoint = api.endPoint();

//Endpoint: http://IP Address:port/iot_aircon_alarm_system_backend/public/?req=vibration&deviceId=$deviceId&limit=1000
Future<List<VibrationList>> fetchVibrationList(String? ipAddress, int? deviceId) async {
  final String baseUrl = 'http://$ipAddress';
  //final response = await http.get(Uri.parse('$endPoint/?req=vibration&deviceId=$deviceId&limit=1000'));
  final response = await http.get(Uri.parse('$baseUrl$endPoint/?req=vibration&deviceId=$deviceId&limit=1000'));
  final responseBody = response.body;
  return compute(parseVibrationList, responseBody);
}

// A function that converts a response body into a List<Device>.
List<VibrationList> parseVibrationList(String responseBody) {
  //Check _JsonMap or List<dynamic>
  final parsedCheck = jsonDecode(responseBody);
  /*
  * Check if single or collection response.
  * If single response or _JsonMap add [] square bracket at the beginning and end of the response.
  */
  if (parsedCheck is! List<dynamic>) { //parsed is not List<dynamic>
    //if parsed is _JsonMap
    responseBody = '[$responseBody]';
  }

  final parsed = jsonDecode(responseBody) as List;
  return parsed
      .map((vibrationData) => VibrationList.fromJson(vibrationData))
      .toList();
}

class VibrationListScreen extends StatefulWidget {
  final String? ipAddress;
  final int? deviceIdSelected;
  final int? userId;
  const VibrationListScreen({Key? key, required this.ipAddress, required this.userId, required this.deviceIdSelected}) : super(key: key);

  @override
  State<VibrationListScreen> createState() => _VibrationListScreenState();
}

class _VibrationListScreenState extends State<VibrationListScreen> {
  late Future<List<VibrationList>> futureVibrationList; // Single or one record

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
    futureVibrationList = fetchVibrationList(widget.ipAddress, widget.deviceIdSelected);
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
          leading: const Icon(Icons.vibration),
          title: const Text('VIBRATION'),
          actions: <Widget>[
            Container(
              decoration: BoxDecoration(
                border: Border.all(color: Colors.lightBlueAccent),
                borderRadius: BorderRadius.circular(3.0),
              ),
              margin: const EdgeInsets.fromLTRB(7.0, 7.0, 7.0, 7.0),
              padding: const EdgeInsets.fromLTRB(7.0, 12.0, 7.0, 12.0),
              child: const Text('AIRCON 1',
                style: TextStyle(fontWeight: FontWeight.bold),
              ),
            ),
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
            padding: const EdgeInsets.fromLTRB(15.0, 5.0, 15.0, 0.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: <Widget>[
                FutureBuilder<List<VibrationList>>(
                  future:
                  futureVibrationList,
                  builder: (context, snapshot) {
                    if (snapshot.hasData) {
                      final List<VibrationList> vibrationListDataItems = snapshot.data!;
                      return Scrollbar(
                        child: SingleChildScrollView(
                          child: PaginatedDataTable(
                            source: dataSource(vibrationListDataItems),
                            columns: const <DataColumn>[
                              DataColumn(
                                label: Expanded(
                                  child: Text('Record ID'),
                                ),
                              ),
                              DataColumn(
                                label: Expanded(
                                  child: Text('Record Time'),
                                ),
                              ),
                              DataColumn(
                                label: Expanded(
                                  child: Text('Vibration'),
                                ),
                              ),
                            ],
                          ),
                        ),
                      );
                    } else if (snapshot.hasError) {
                      return const Center( child:Text('No Data Available.', style: TextStyle(color: Colors.white,),),);
                    }
                    // By default, show a loading spinner.
                    return const Center(
                      child: CircularProgressIndicator(),
                    );
                  },
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
                      builder: (context) => MainMenuScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                          deviceIdSelected: widget.deviceIdSelected),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.chevron_left,
                  size: 24.0,
                ),
                label: const Text('Back to Main Menu'),
              ),
            ),
            Expanded(
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(padding: const EdgeInsets.only(top: 16.0, bottom: 16.0) ),
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => SensorDataScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                          deviceIdSelected: widget.deviceIdSelected),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.checklist,
                  size: 24.0,
                ),
                label: const Text('Back to Sensor Data'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

DataTableSource dataSource(List<VibrationList> vibrationList) =>
    VibrationDataSource(dataList: vibrationList);

class VibrationDataSource extends DataTableSource {
  final List<VibrationList> dataList;
  VibrationDataSource({required this.dataList});

  @override
  DataRow? getRow(int index) {
    assert(index >= 0);
    if (index >= dataList.length) return null;
    final vibration = dataList[index];
    return DataRow.byIndex(
      index: index,
      cells: [
        DataCell(Text(vibration.recordId.toString())),
        DataCell(Text(vibration.recordTime.toString())),
        DataCell(Text(vibration.rData.toString() + ' m/s^2')),
      ],
    );
  }

  @override
  int get rowCount => dataList.length;

  @override
  bool get isRowCountApproximate => false;

  @override
  int get selectedRowCount => 0;
}