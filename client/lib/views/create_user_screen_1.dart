import 'dart:async';
import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/main_menu_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/device_menu_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/user_model.dart';
import 'package:iot_aircon_alarm_system/models/user_group_model.dart';

import 'package:iot_aircon_alarm_system/models/api.dart';

final api = Api();
final endPoint = api.endPoint();

//Create
//Endpoint: http://IP Address:port
Future<User> createUser(
    String? ipAddress,
    String? username,
    String? password,
    String? email,
    String? groupId,
    String? firstName,
    String? lastName,
    String? middleName) async {
  final String baseUrl = 'http://$ipAddress';
  final response =
      //await http.post(Uri.parse('$endPoint/userCreate.php'), body: {
    await http.post(Uri.parse('$baseUrl$endPoint/userCreate.php'), body: {
    //Correct
    'username': username,
    'password': password,
    'email': email,
    'groupId': groupId,
    'firstName': firstName,
    'lastName': lastName,
    'middleName': middleName,
  });

  if (response.statusCode == 200) {
    return User.fromJson(jsonDecode(response.body));
  } else {
    throw Exception('Failed to create user.');
  }
}

//Create/Update
//Endpoint: http://IP Address:port
Future<User> createOrUpdateUser(
    String? ipAddress,
    String? username,
    String? password,
    String? email,
    String? groupId,
    String? firstName,
    String? lastName,
    String? middleName) async {
  final String baseUrl = 'http://$ipAddress';
  //final response = await http.post(Uri.parse('$endPoint/user.php'), body: {
  final response = await http.post(Uri.parse('$baseUrl/user.php'), body: {
    //Correct
    'username': username,
    'password': password,
    'email': email,
    'groupId': groupId,
    'firstName': firstName,
    'lastName': lastName,
    'middleName': middleName,
  });

  if (response.statusCode == 200) {
    return User.fromJson(jsonDecode(response.body));
  } else {
    throw Exception('Failed to create user.');
  }
}

Future<List<UserGroup>> fetchUserGroup(String? ipAddress) async {
  final String baseUrl = 'http://$ipAddress';
  //final response = await http.get(Uri.parse('$endPoint/?req=userGroup'));
  final response = await http.get(Uri.parse('$baseUrl$endPoint/?req=userGroup'));
  final responseBody = response.body;
  return compute(parseUserGroup, responseBody);
}

// A function that converts a response body into a List<Device>.
List<UserGroup> parseUserGroup(String responseBody) {
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
  return parsed
      .map((userGroupData) => UserGroup.fromJson(userGroupData))
      .toList();
}

class CreateUserScreen extends StatefulWidget {
  final String? ipAddress;
  final int? userId;
  const CreateUserScreen({Key? key, required this.ipAddress, required this.userId}) : super(key: key);

  @override
  State<CreateUserScreen> createState() => _CreateUserScreenState();
}

class _CreateUserScreenState extends State<CreateUserScreen> {
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _middleNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();

  String? usernameValue = "";
  String? passwordValue = "";
  String? emailValue = "";
  String? groupIdValue = "";
  String? firstNameValue = "";
  String? middleNameValue = "";
  String? lastNameValue = "";
  String? groupDataValue; //Don't set to an Empty String
  Future<User>? _futureCreateUser;

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
          leading: const Icon(Icons.person_add),
          title: const Text('ADD NEW USER'),
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
            alignment: Alignment.center,
            padding: const EdgeInsets.all(30.0),
            child: (_futureCreateUser == null)
                ? buildColumnCreateUser()
                : buildFutureBuilderCreateUser(),
          ),
        ),
      ),
    );
  }

  Column buildColumnCreateUser() {
    return Column(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: <Widget>[
        Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: <Widget>[
            TextField(
              controller: _firstNameController,
              decoration: const InputDecoration(labelText: 'FIRST NAME'),
            ),
            const SizedBox(height: 15.0),
            TextField(
              controller: _lastNameController,
              decoration: const InputDecoration(labelText: 'LAST NAME'),
            ),
            const SizedBox(height: 15.0),
            TextField(
              controller: _middleNameController,
              decoration: const InputDecoration(labelText: 'MIDDLE NAME'),
            ),
            const SizedBox(height: 15.0),
            //dropdown for group id
            FutureBuilder<List<UserGroup>>(
              future: fetchUserGroup(_ipAddress),
              builder: (context, snapshot) {
                if (snapshot.hasData) {
                  final List<UserGroup> userGroupDataItems = snapshot.data!;
                  return Container(
                    padding: const EdgeInsets.all(5.0),
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
                        hint: const Text(
                          'CHOOSE USER GROUP',
                          style: TextStyle(color: Colors.white),
                        ),
                        disabledHint: const Text('CHOOSE USER GROUP'),
                        value: groupDataValue,
                        items: userGroupDataItems.map((UserGroup data) {
                          return DropdownMenuItem<String>(
                            value: data.groupId,
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
                            groupDataValue = value!;
                          });
                        },
                        isExpanded: true,
                      ),
                    ),
                  );
                } else if (snapshot.hasError) {
                  //return Text('${snapshot.error}');
                  return const Text('Loading User Group...', style: TextStyle(color: Colors.white,));
                }
                // By default, show a loading spinner.
                return const CircularProgressIndicator();
              },
            ),
            const SizedBox(height: 15.0),
            TextField(
              controller: _usernameController,
              decoration: const InputDecoration(
                labelText: 'USERNAME',
              ),
            ),
            //SizedBox(height: 15.0),
            const SizedBox(height: 15.0),
            TextField(
              controller: _passwordController,
              obscureText: true,
              decoration: const InputDecoration(labelText: 'PASSWORD'),
            ),
            const SizedBox(height: 15.0),
            TextField(
              controller: _emailController,
              decoration: const InputDecoration(labelText: 'EMAIL'),
            ),
            const SizedBox(height: 30.0),
            ElevatedButton(
              onPressed: () {
                //createAlarm('1');
                setState(() {
                  _futureCreateUser = createUser(
                      _ipAddress,
                      _usernameController.text,
                      _passwordController.text,
                      _emailController.text,
                      groupDataValue.toString(),
                      _firstNameController.text,
                      _lastNameController.text,
                      _middleNameController.text);
                });
              },
              child: const Text(
                'SAVE',
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
        const SizedBox(width: double.infinity, height: 15.0),
        Column(
          mainAxisAlignment: MainAxisAlignment.end,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: <Widget>[
            ElevatedButton.icon(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => DeviceMenuScreen(ipAddress: _ipAddress,
                        userId: _userId, userGroupId: _groupId),
                  ),
                );
              },
              icon: const Icon(
                Icons.home,
                size: 42.0,
              ),
              label: const Text('Main Menu'),
            ),
          ],
        ),
      ],
    );
  }

  FutureBuilder<User> buildFutureBuilderCreateUser() {
    return FutureBuilder<User>(
      future: _futureCreateUser,
      builder: (context, snapshot) {
        if (snapshot.hasData) {
          return Column(
            children: <Widget>[
              Text(snapshot.data!.message.toString(), style: TextStyle(color: Colors.white,),),
              const SizedBox(
                height: 30.0,
              ),
              ElevatedButton.icon(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) =>
                          CreateUserScreen(ipAddress: _ipAddress, userId: widget.userId),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.login,
                  size: 36.0,
                ),
                label: const Text('Go back to the Create User page',),
              ),
            ],
          );
        } else if (snapshot.hasError) {
          return Column(
            children: <Widget>[
              const Text('Error', style: TextStyle(color: Colors.white,),),
              const SizedBox(
                height: 30.0,
              ),
              ElevatedButton.icon(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) =>
                          CreateUserScreen(ipAddress: _ipAddress, userId: widget.userId),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.login,
                  size: 36.0,
                ),
                label: const Text('Go back to the Create User page'),
              ),
            ],
          );
        }
        return const CircularProgressIndicator();
      },
    );
  }
}
