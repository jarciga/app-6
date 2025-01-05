<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class SensorModel
{
    protected $connection = null;

    public function __construct() 
    {
        $this->connection = new Connection();
    }
   
    public function getDBConnection()
    {
        return $this->connection->getMysqliDB();
    }

    public function SensorDataLatestTemperature($deviceId = null) 
    {
        $sql = 'SELECT `record_id`, `device_id`, `temp_data`, `record_time` FROM `data_temp_hmd`'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE `device_id` = ' . intval($deviceId);        
        }
        
        $sql .= ' ORDER BY `record_id` DESC';
        $sql .=  ' LIMIT 1';


        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestTemperature = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrSensorDataLatestTemperature = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'temp_data' => floatval($row['temp_data']),
                'record_time' => strval($row['record_time']),
            );
        } 
        else 
        {
            return '0 result(s)';
        }
        return $arrSensorDataLatestTemperature;
    }

    
    public function SensorDataLatestHumidity($deviceId = null) 
    {
        $sql = 'SELECT `record_id`, `device_id`, `hmd_data`, `record_time` FROM `data_temp_hmd`'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE `device_id` = ' . intval($deviceId);        
        }
        
        $sql .= ' ORDER BY `record_id` DESC';
        $sql .=  ' LIMIT 1';

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestHumidity = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrSensorDataLatestHumidity = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'hmd_data' => floatval($row['hmd_data']),
                'record_time' => strval($row['record_time']),
            );
        } 
        else 
        {
            return '0 result(s)';
        }
        return $arrSensorDataLatestHumidity;
    }

    public function SensorDataLatestCurrent($deviceId = null) 
    {
        $sql = 'SELECT `record_id`, `device_id`, `amp_data`, `record_time` FROM `data_amp`'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE `device_id` = ' . intval($deviceId);        
        }
        
        $sql .= ' ORDER BY `record_id` DESC';
        $sql .=  ' LIMIT 1';

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestCurrent = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrSensorDataLatestCurrent = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'amp_data' => floatval($row['amp_data']),
                'record_time' => strval($row['record_time']),
            );
        } 
        else 
        {
            return '0 result(s)';
        }
        return $arrSensorDataLatestCurrent;
    }


    public function SensorDataLatestRefrigerant($deviceId = null) 
    {
        $sql = 'SELECT `record_id`, `device_id`, `vout_data`, `vref_data`, `vout_status`, `vref_status`, `alarm_status`, `record_time` FROM `data_gas`'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE `device_id` = ' . intval($deviceId);        
        }
        
        $sql .= ' ORDER BY `record_id` DESC';
        $sql .=  ' LIMIT 1';


        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestRefrigerant = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

           if (floatval($row['vout_data']) >= floatval($row['vref_data']))
            {
                //$recommendation = 'Check for Refrigerant Leakage';
                $recommendation = 'Abnormal';
            }
            else 
            {
                $recommendation = 'Normal';
            }

            //$arrSensor['data'] = array(
            //$arrSensor[] = array(
            $arrSensorDataLatestRefrigerant = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'vout_data' => floatval($row['vout_data']),
                'vref_data' => floatval($row['vref_data']),
                'vout_status' => strval($row['vout_status']),
                'vref_status' => strval($row['vref_status']),
                'alarm_status' => strval($row['alarm_status']),
                'record_time' => strval($row['record_time']),
                'recommendation' => strval($recommendation),
            );
        } 
        else 
        {
            return '0 result(s)';
        }
        return $arrSensorDataLatestRefrigerant;
    }

    public function SensorDataLatestVibration($deviceId = null) 
    {
        $sql = 'SELECT dtvibr1.*, alrmparam1.* FROM `data_vibration` dtvibr1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtvibr1.device_id = alrmparam1.device_id'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtvibr1.`device_id` = ' . intval($deviceId);        
        }

        $sql .= ' ORDER BY dtvibr1.`record_time` DESC';
        $sql .=  ' LIMIT 1';

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestVibration = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            //if (floatval($row['r_data'] > 22.0))
            if (floatval($row['r_data']) > floatval($row['vibration']))
            {
                //$recommendation = 'Check for faulty compressor or any loose screws';
                $recommendation = 'Abnormal';
            }
            else 
            {
                $recommendation = 'Normal';
            }
            
            $arrSensorDataLatestVibration = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'r_data' => floatval($row['r_data']),
                'record_time' => strval($row['record_time']),
                'recommendation' => strval($recommendation),
            );
        } 
        else 
        {
            return '0 result(s)';
        }
        return $arrSensorDataLatestVibration;
    }
     
    public function sensorQuery($deviceId = null) 
    {
        //$deviceName = !empty($this->device->deviceById($deviceId)['temp_data']) ? $this->device->deviceById($deviceId)['name'] : null;
        $temperature = !empty($this->SensorDataLatestTemperature($deviceId)['temp_data']) ? $this->SensorDataLatestTemperature($deviceId)['temp_data'] : 0;
        $humidity = !empty($this->SensorDataLatestHumidity($deviceId)['hmd_data']) ? $this->SensorDataLatestHumidity($deviceId)['hmd_data'] : 0;
        $current = !empty($this->SensorDataLatestCurrent($deviceId)['amp_data']) ? $this->SensorDataLatestCurrent($deviceId)['amp_data'] : 0;
        
        $refrigerantVoutData = !empty($this->SensorDataLatestRefrigerant($deviceId)['vout_data']) ? $this->SensorDataLatestRefrigerant($deviceId)['vout_data'] : 0;
        $refrigerantVrefData = !empty($this->SensorDataLatestRefrigerant($deviceId)['vref_data']) ? $this->SensorDataLatestRefrigerant($deviceId)['vref_data'] : 0;
        $refrigerantRecommendation = !empty($this->SensorDataLatestRefrigerant($deviceId)['recommendation']) ? $this->SensorDataLatestRefrigerant($deviceId)['recommendation'] : null;
        
        $vibration = !empty($this->SensorDataLatestVibration($deviceId)['r_data']) ? $this->SensorDataLatestVibration($deviceId)['r_data'] : 0;
        $vibrationRecommendation = !empty($this->SensorDataLatestVibration($deviceId)['recommendation']) ? $this->SensorDataLatestVibration($deviceId)['recommendation'] : null;

        $arrSensorData = array(
            'device_id' => $deviceId,
            //'device_name' => $deviceName,
            'temperature' => $temperature,
            'humidity' => $humidity,
            'current' => $current,
            'refrigerant_vout_data' => $refrigerantVoutData,
            'refrigerant_vref_data' => $refrigerantVrefData,
            'refrigerant_recommendation' => $refrigerantRecommendation,
            'vibration' => $vibration,
            'vibration_recommendation' => $vibrationRecommendation,
        );

        //Close the database connection
        $this->connection->getMysqliDB()->close();

        return $arrSensorData;
    }
}