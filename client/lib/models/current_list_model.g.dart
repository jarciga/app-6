// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'current_list_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

CurrentList _$CurrentListFromJson(Map<String, dynamic> json) => CurrentList(
      recordId: json['record_id'] as int?,
      deviceId: json['device_id'] as int?,
      ampData: (json['amp_data'] as num?)?.toDouble(),
      recordTime: json['record_time'] as String?,
      recommendation: json['recommendation'] as String?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$CurrentListToJson(CurrentList instance) =>
    <String, dynamic>{
      'record_id': instance.recordId,
      'device_id': instance.deviceId,
      'amp_data': instance.ampData,
      'record_time': instance.recordTime,
      'recommendation': instance.recommendation,
      'message': instance.message,
    };
