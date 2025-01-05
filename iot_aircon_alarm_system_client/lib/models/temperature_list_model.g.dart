// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'temperature_list_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

TemperatureList _$TemperatureListFromJson(Map<String, dynamic> json) =>
    TemperatureList(
      recordId: json['record_id'] as int?,
      deviceId: json['device_id'] as int?,
      tempData: (json['temp_data'] as num?)?.toDouble(),
      recordTime: json['record_time'] as String?,
      recommendation: json['recommendation'] as String?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$TemperatureListToJson(TemperatureList instance) =>
    <String, dynamic>{
      'record_id': instance.recordId,
      'device_id': instance.deviceId,
      'temp_data': instance.tempData,
      'record_time': instance.recordTime,
      'recommendation': instance.recommendation,
      'message': instance.message,
    };
