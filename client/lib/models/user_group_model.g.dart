// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'user_group_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

UserGroup _$UserGroupFromJson(Map<String, dynamic> json) => UserGroup(
      groupId: json['group_id'] as String?,
      name: json['name'] as String?,
      description: json['description'] as String?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$UserGroupToJson(UserGroup instance) => <String, dynamic>{
      'group_id': instance.groupId,
      'name': instance.name,
      'description': instance.description,
      'message': instance.message,
    };
