import 'package:json_annotation/json_annotation.dart';

part 'user_model.g.dart';

@JsonSerializable()
class User {
  //From: user_credentials table
  @JsonKey(name: 'user_id')
  final int? userId;

  //@JsonKey(name: 'username')
  final String? username;

  //@JsonKey(name: 'password')
  final String? password;

  //@JsonKey(name: 'email')
  final String? email;

  //@JsonKey(name: 'group_id')
  final String? groupId;

  //@JsonKey(name: 'create_date')
  //final String? createDate;

  //@JsonKey(name: 'update_date')
  //final String? updateDate;

  //From: user_data table
  //@JsonKey(name: 'first_name')
  final String? firstName;

  //@JsonKey(name: 'last_name')
  final String? lastName;

  //@JsonKey(name: 'middle_name')
  final String? middleName;

  //@JsonKey(name: 'message')
  final String? message;

  User({
    required this.userId,
    required this.username,
    required this.password,
    required this.email,
    required this.groupId,
    required this.firstName,
    required this.lastName,
    required this.middleName,
    required this.message
  });

  factory User.fromJson(Map<String, dynamic> json) => _$UserFromJson(json);

  Map<String, dynamic> toJson() => _$UserToJson(this);
}