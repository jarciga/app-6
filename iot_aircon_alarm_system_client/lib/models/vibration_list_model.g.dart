// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'vibration_list_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

VibrationList _$VibrationListFromJson(Map<String, dynamic> json) =>
    VibrationList(
      recordId: json['record_id'] as int?,
      deviceId: json['device_id'] as int?,
      rData: (json['r_data'] as num?)?.toDouble(),
      recordTime: json['record_time'] as String?,
      recommendation: json['recommendation'] as String?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$VibrationListToJson(VibrationList instance) =>
    <String, dynamic>{
      'record_id': instance.recordId,
      'device_id': instance.deviceId,
      'r_data': instance.rData,
      'record_time': instance.recordTime,
      'recommendation': instance.recommendation,
      'message': instance.message,
    };
