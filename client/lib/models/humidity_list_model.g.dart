// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'humidity_list_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

HumidityList _$HumidityListFromJson(Map<String, dynamic> json) => HumidityList(
      recordId: json['record_id'] as int?,
      deviceId: json['device_id'] as int?,
      hmdData: (json['hmd_data'] as num?)?.toDouble(),
      recordTime: json['record_time'] as String?,
      recommendation: json['recommendation'] as String?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$HumidityListToJson(HumidityList instance) =>
    <String, dynamic>{
      'record_id': instance.recordId,
      'device_id': instance.deviceId,
      'hmd_data': instance.hmdData,
      'record_time': instance.recordTime,
      'recommendation': instance.recommendation,
      'message': instance.message,
    };
