// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'device_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

Device _$DeviceFromJson(Map<String, dynamic> json) => Device(
      deviceId: json['device_id'] as String?,
      name: json['name'] as String?,
      type: json['type'] as String?,
      description: json['description'] as String?,
      createDate: json['create_date'] as String?,
      updateDate: json['update_date'] as String?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$DeviceToJson(Device instance) => <String, dynamic>{
      'device_id': instance.deviceId,
      'name': instance.name,
      'type': instance.type,
      'description': instance.description,
      'create_date': instance.createDate,
      'update_date': instance.updateDate,
      'message': instance.message,
    };
