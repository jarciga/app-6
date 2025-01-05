<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class SensorNotificationModel
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
        $sql = 'SELECT dtth1.*, alrmparam1.* FROM `data_temp_hmd` dtth1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtth1.device_id = alrmparam1.device_id'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtth1.`device_id` = ' . intval($deviceId);        
        }
        
        $sql .= ' ORDER BY dtth1.`record_time` DESC';
        $sql .=  ' LIMIT 1';

        //echo $sql;

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestTemperature = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrSensorDataLatestTemperature = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'temp_data' => floatval($row['temp_data']),
                //'record_time' => strval($row['record_time']),
                'temperature' => floatval($row['temperature']),
                'record_time' => strval($row['record_time']),
                'sensor_name' => strtoupper(strval('Temperature')),
            );
        } 
        else 
        {
            //return '0 result(s)';
            return $arrSensorDataLatestTemperature = array(
                'record_id' => 0,
                'device_id' => 0,
                'temp_data' => 0.0,
                'temperature' => 0.0,
                'record_time' => "",
                'sensor_name' => strtoupper(strval('Temperature')),
            );
        }

        return $arrSensorDataLatestTemperature;
    }

    
    public function SensorDataLatestHumidity($deviceId = null) 
    {
        $sql = 'SELECT dtth1.*, alrmparam1.* FROM `data_temp_hmd` dtth1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtth1.device_id = alrmparam1.device_id'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtth1.`device_id` = ' . intval($deviceId);    
        }
        
        $sql .= ' ORDER BY dtth1.`record_time` DESC';
        $sql .=  ' LIMIT 1';

        //echo $sql;

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestHumidity = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrSensorDataLatestHumidity = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'temp_data' => floatval($row['temp_data']),
                'temperature' => floatval($row['temperature']),
                //'humidity' => floatval($row['humidity']),
                'humid_max' => floatval($row['humid_max']),
                'humid_min' => floatval($row['humid_min']),
                'hmd_data' => floatval($row['hmd_data']),
                'record_time' => strval($row['record_time']),
                'sensor_name' => strtoupper(strval('Humidity')),
            );
        } 
        else 
        {
            //return '0 result(s)';
            return $arrSensorDataLatestHumidity = array(
                'record_id' => 0,
                'device_id' => 0,
                'temp_data' => 0.0,
                'temperature' => 0.0,
                //'humidity' => 0.0,
                'humid_max' => 0.0,
                'humid_min' => 0.0,
                'hmd_data' => 0.0,
                'record_time' => "",
                'sensor_name' => strtoupper(strval('Humidity')),
            );
        }

        return $arrSensorDataLatestHumidity;
    }

    public function SensorDataLatestCurrent($deviceId = null) 
    {
        $sql = 'SELECT dta1.*, alrmparam1.* FROM `data_amp` dta1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dta1.device_id = alrmparam1.device_id'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dta1.`device_id` = ' . intval($deviceId);        
        }
        
        $sql .= ' ORDER BY dta1.`record_time` DESC';
        $sql .=  ' LIMIT 1';

        //echo $sql;

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestCurrent = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrSensorDataLatestCurrent = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'amp_data' => floatval($row['amp_data']),
                'current' => floatval($row['current']),
                'current_multiplier' => floatval($row['current_multiplier']),
                //'current_max' => floatval($row['current_max']),
                //'current_min' => floatval($row['current_min']),
                'record_time' => strval($row['record_time']),
                'sensor_name' => strtoupper(strval('Current')),
            );
        } 
        else 
        {
            //return '0 result(s)';
            return $arrSensorDataLatestCurrent = array(
                'record_id' => 0,
                'device_id' => 0,
                'amp_data' => 0.0,
                'current' => 0.0,
                'current_multiplier' =>  0.0,
                //'current_max' => 0.0,
                //'current_min' => 0.0,
                'record_time' => "",
                'sensor_name' => strtoupper(strval('Current')),
            );
        }

        return $arrSensorDataLatestCurrent;
    }


    public function SensorDataLatestRefrigerant($deviceId = null) 
    {
        $sql = 'SELECT dtg1.*, alrmparam1.* FROM `data_gas` dtg1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtg1.device_id = alrmparam1.device_id'; 

        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtg1.`device_id` = ' . intval($deviceId);       
        }
        
        $sql .= ' ORDER BY dtg1.`record_time` DESC';
        $sql .=  ' LIMIT 1';

        //echo $sql;

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestRefrigerant = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

           if (floatval($row['vout_data']) >= floatval($row['vref_data']))
            {
                $recommendation = 'Check for Refrigerant Leakage';
            }
            else 
            {
                $recommendation = 'Normal';
            }

            $arrSensorDataLatestRefrigerant = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'vout_data' => floatval($row['vout_data']),
                'vref_data' => floatval($row['vref_data']),
                'vout_status' => strval($row['vout_status']),
                'vref_status' => strval($row['vref_status']),
                'alarm_status' => strval($row['alarm_status']),
                'record_time' => strval($row['record_time']),
                'sensor_name' => strtoupper(strval('Refrigerant')),
            );
        } 
        else 
        {
            //return '0 result(s)';
            return $arrSensorDataLatestRefrigerant = array(
                'record_id' => 0,
                'device_id' => 0,
                'vout_data' => 0.0,
                'vref_data' => 0.0,
                'vout_status' => "",
                'vref_status' => "",
                'alarm_status' => "",
                'record_time' => "",
                'sensor_name' => strtoupper(strval('Refrigerant')),
            );
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

        //echo $sql;

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrSensorDataLatestVibration = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            //if (floatval($row['r_data'] > 22.0))
            if (floatval($row['r_data']) > floatval($row['vibration']))
            {
                $recommendation = 'Check for faulty compressor or any loose screws';
            }
            else 
            {
                $recommendation = 'Normal';
            }
            $arrSensorDataLatestVibration = array(
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'r_data' => floatval($row['r_data']),
                'vibration' => floatval($row['vibration']),
                //'vibration_max' => floatval($row['vibration_max']),
                //'vibration_min' => floatval($row['vibration_min']),
                'record_time' => strval($row['record_time']),
                'sensor_name' => strtoupper(strval('Vibration')),
            );
        } 
        else 
        {
            //return '0 result(s)';
            return $arrSensorDataLatestVibration = array(
                'record_id' => 0,
                'device_id' => 0,
                'r_data' => 0.0,
                'vibration' => 0.0,
                //'vibration_max' => 0.0,
                //'vibration_min' => 0.0,
                'record_time' => "",
                'sensor_name' => strtoupper(strval('Vibration')),
            );
        }

        return $arrSensorDataLatestVibration;
    }
}