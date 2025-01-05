// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'alarm_params_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

AlarmParams _$AlarmParamsFromJson(Map<String, dynamic> json) => AlarmParams(
      alarmId: json['alarm_id'] as int?,
      name: json['name'] as String?,
      deviceId: json['deviceId'] as int?,
      userId: json['userId'] as int?,
      temperature: (json['temperature'] as num?)?.toDouble(),
      current: (json['current'] as num?)?.toDouble(),
      humidity: (json['humidity'] as num?)?.toDouble(),
      message: json['message'] as String?,
);

Map<String, dynamic> _$AlarmParamsToJson(AlarmParams instance) =>
    <String, dynamic>{
          'alarm_id': instance.alarmId,
          'name': instance.name,
          'deviceId': instance.deviceId,
          'userId': instance.userId,
          'temperature': instance.temperature,
          'current': instance.current,
          'humidity': instance.humidity,
          'message': instance.message,
    };
