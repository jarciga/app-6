// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'login_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

Login _$LoginFromJson(Map<String, dynamic> json) => Login(
      userId: json['user_id'] as int?,
      username: json['username'] as String?,
      password: json['password'] as int?,
      groupId: json['group_id'] as int?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$LoginToJson(Login instance) => <String, dynamic>{
      'user_id': instance.userId,
      'username': instance.username,
      'password': instance.password,
      'group_id': instance.groupId,
      'message': instance.message,
    };
