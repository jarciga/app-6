<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class TemperatureModel 
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
     
    public function temperatureQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT `record_id`, `device_id`, `temp_data`, `record_time` FROM `data_temp_hmd`'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE `device_id` = ' . intval($deviceId);        
        }

        $sql .= ' ORDER BY `record_time` DESC';

        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }
        
        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrTemperature = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrTemperature = array(
                'message' => strval('Success'),
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'temp_data' => floatval($row['temp_data']),
                //'hmd_data' => $row['hmd_data'],
                'record_time' => strval($row['record_time']),
            );
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
                $arrTemperature[] = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'temp_data' => floatval($row['temp_data']),
                    //'hmd_data' => $row['hmd_data'],
                    'record_time' => strval($row['record_time']),
                );
            }
        } 
        else 
        {
            return '0 result(s)';
            //return $arrCurrent = array('message' => 'Error');
        }

        $this->connection->getMysqliDB()->close();

        return $arrTemperature;
    }

    public function temperatureAlarmHistoryQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT dtth1.*, alrmparam1.* FROM `data_temp_hmd` dtth1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtth1.device_id = alrmparam1.device_id'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtth1.`device_id` = ' . intval($deviceId);        
        }

        $sql .= ' AND dtth1.`temp_data` > alrmparam1.`temperature`';

        $sql .= ' ORDER BY dtth1.`record_time` DESC';

        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }
        
        //echo $sql . "\n\n";

        $result =  $this->connection->getMysqliDB()->query($sql);
        
        //echo $result->num_rows . "\n\n";

        $arrTemperature = array();
        $recommendation = '';
        $tempUserAlarmParam = 0;
        $tempThresholdValue = 0;

        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            $tempUserAlarmParam = (floatval($row['temperature']));
            $tempThresholdValue = floatval($row['temp_data']) + $tempUserAlarmParam;


            //$tempUserAlarmParam = (floatval($row['temperature']) + 0.5);
            //$tempUserAlarmParam = (floatval($row['temperature']));

            //if ( ( floatval($row['temp_data']) > $tempUserAlarmParam ) )
            ////if ( ( floatval($row['temp_data']) > $tempThresholdValue ) )
            //{
                $recommendation = "Frozen Coil: Check for a Frozen coil and the airflow";

                $arrTemperature = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'temp_data' => floatval($row['temp_data']),
                    'user_input_temperature' => floatval($row['temperature']),
                    'record_time' => strval($row['record_time']),
                    'recommendation' => strval($recommendation),
                );
            //}
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {

                $tempUserAlarmParam = (floatval($row['temperature']));
                $tempThresholdValue = floatval($row['temp_data']) + $tempUserAlarmParam;

                //$tempUserAlarmParam = (floatval($row['temperature']) + 0.5);
                //$tempUserAlarmParam = (floatval($row['temperature']));
                
                //if ( ( floatval($row['temp_data']) > $tempUserAlarmParam ) )
                ////if ( ( floatval($row['temp_data']) > $tempThresholdValue ) )
                //{
                    $recommendation = "Frozen Coil: Check for a Frozen coil and the airflow";

                    $arrTemperature[] = array(
                        'message' => strval('Success'),
                        'record_id' => intval($row['record_id']),
                        'device_id' => intval($row['device_id']),
                        'temp_data' => floatval($row['temp_data']),
                        'user_input_temperature' => floatval($row['temperature']),
                        'record_time' => strval($row['record_time']),
                        'recommendation' => strval($recommendation),
                    );
                //}
            }
        } 
        else 
        {
            return '0 result(s)';
            //return $arrCurrent = array('message' => 'Error');
        }

        $this->connection->getMysqliDB()->close();

        return $arrTemperature;
    }

    public function temperatureAndHumidityAlarmHistoryQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT dtth1.*, alrmparam1.* FROM `data_temp_hmd` dtth1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtth1.device_id = alrmparam1.device_id'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtth1.`device_id` = ' . intval($deviceId);        
        }

        $sql .= ' ORDER BY dtth1.`record_time` DESC';

        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }
        
        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrTemperature = array();
        $recommendation = '';
        $tempUserAlarmParam = 0;
        $tempThresholdValue = 0;

        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            $tempUserAlarmParam = (floatval($row['temperature']));
            $tempThresholdValue = floatval($row['temp_data']) + $tempUserAlarmParam;

            //$tempUserAlarmParam = (floatval($row['temperature']) + 0.5);
            //$tempUserAlarmParam = (floatval($row['temperature']));

            if ( ( floatval($row['hmd_data']) > 60.0 ) && 
            ( floatval($row['temp_data']) > $tempUserAlarmParam ) )

            //if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
            //( floatval($row['temp_data']) > $tempThresholdValue ) )
            {
                $recommendation = "Install Dehumidifier or else more strain in AC unit and High Current Consumption";

                $arrTemperature = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'temp_data' => floatval($row['temp_data']),
                    'user_input_temperature' => floatval($row['temperature']),
                    'hmd_data' => floatval($row['hmd_data']),
                    'record_time' => strval($row['record_time']),
                    'recommendation' => strval($recommendation),
                );
            }
            
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {

                $tempUserAlarmParam = (floatval($row['temperature']));
                $tempThresholdValue = floatval($row['temp_data']) + $tempUserAlarmParam;

                //$tempUserAlarmParam = (floatval($row['temperature']) + 0.5);
                //$tempUserAlarmParam = (floatval($row['temperature']));

                if ( ( floatval($row['hmd_data']) > 60.0 ) && 
                ( floatval($row['temp_data']) > $tempUserAlarmParam ) )
                //if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
                //( floatval($row['temp_data']) > $tempThresholdValue ) )
                {
                    $recommendation = "Install Dehumidifier or else more strain in AC unit and High Current Consumption";

                    $arrTemperature[] = array(
                        'message' => strval('Success'),
                        'record_id' => intval($row['record_id']),
                        'device_id' => intval($row['device_id']),
                        'temp_data' => floatval($row['temp_data']),
                        'user_input_temperature' => floatval($row['temperature']),
                        'hmd_data' => floatval($row['hmd_data']),
                        'record_time' => strval($row['record_time']),
                        'recommendation' => strval($recommendation),
                    );
                }
                
            }
        } 
        else 
        {
            return '0 result(s)';
            //return $arrCurrent = array('message' => 'Error');
        }

        $this->connection->getMysqliDB()->close();

        return $arrTemperature;
    }   
}