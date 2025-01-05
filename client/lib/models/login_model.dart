import 'package:json_annotation/json_annotation.dart';

part 'login_model.g.dart';

@JsonSerializable()
class Login {
  @JsonKey(name: 'user_id')
  final int? userId;

  //@JsonKey(name: 'username')
  final String? username;

  //@JsonKey(name: 'password')
  final int? password;

  @JsonKey(name: 'group_id')
  final int? groupId;

  //@JsonKey(name: 'message')
  final String? message;

  Login({
    required this.userId,
    required this.username,
    required this.password,
    required this.groupId,
    required this.message
  });

  factory Login.fromJson(Map<String, dynamic> json) => _$LoginFromJson(json);

  Map<String, dynamic> toJson() => _$LoginToJson(this);
}