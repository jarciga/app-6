// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'sensor_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

Sensor _$SensorFromJson(Map<String, dynamic> json) => Sensor(
      deviceId: json['device_id'] as int?,
      temperature: (json['temperature'] as num?)?.toDouble(),
      humidity: (json['humidity'] as num?)?.toDouble(),
      current: (json['current'] as num?)?.toDouble(),
      refrigerantVOutData: (json['refrigerant_vout_data'] as num?)?.toDouble(),
      refrigerantVRefData: (json['refrigerant_vref_data'] as num?)?.toDouble(),
      refrigerantRecommendation: json['refrigerant_recommendation'] as String?,
      vibrationRecommendation: json['vibration_recommendation'] as String?,
      vibration: (json['vibration'] as num?)?.toDouble(),
      message: json['message'] as String?,
    );

Map<String, dynamic> _$SensorToJson(Sensor instance) => <String, dynamic>{
      'device_id': instance.deviceId,
      'temperature': instance.temperature,
      'humidity': instance.humidity,
      'current': instance.current,
      'refrigerant_vout_data': instance.refrigerantVOutData,
      'refrigerant_vref_data': instance.refrigerantVRefData,
      'refrigerant_recommendation': instance.refrigerantRecommendation,
      'vibration': instance.vibration,
      'vibration_recommendation': instance.vibrationRecommendation,
      'message': instance.message,
    };
