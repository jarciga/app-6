<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class HumidityModel 
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
     
    public function humidityQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT `record_id`, `device_id`, `hmd_data`, `record_time` FROM `data_temp_hmd`'; 
        
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

        $arrHumidity = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrHumidity = array(
                'message' => strval('Success'),
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'hmd_data' => floatval($row['hmd_data']),
                'record_time' => strval($row['record_time']),
            );
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
                $arrHumidity[] = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'hmd_data' => floatval($row['hmd_data']),
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

        return $arrHumidity;
    }

    public function humidityAlarmHistoryQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT dtth1.*, alrmparam1.* FROM `data_temp_hmd` dtth1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtth1.`device_id` = alrmparam1.`device_id`'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtth1.`device_id` = ' . intval($deviceId);        
        }

        $sql .= ' AND ((dtth1.`hmd_data` > alrmparam1.`humid_max`)';
        $sql .= ' OR (dtth1.`hmd_data` < alrmparam1.`humid_min`))';

        $sql .= ' ORDER BY dtth1.`record_time` DESC';

        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }
        
        //echo $sql . "\n\n";

        $result = $this->connection->getMysqliDB()->query($sql);

        //echo $result->num_rows . "\n\n";

        $arrHumidity = array();
        $recommendation = '';
        $tempUserAlarmParam = 0;
        $tempThresholdValue = 0;

        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            $tempUserAlarmParam = (floatval($row['temperature']));
            $tempThresholdValue = floatval($row['temp_data']) + $tempUserAlarmParam;

            //if ( ( floatval($row['hmd_data']) > 60.0 ) && 
            //if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
            //( floatval($row['temp_data']) > $tempUserAlarmParam ) )

            //if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
            //( floatval($row['temp_data']) > $tempThresholdValue ) )
            if ( floatval($row['hmd_data']) > floatval($row['humid_max']) )
            {
                $recommendation = "Install Dehumidifier or else more strain in AC unit and High Current Consumption";
            }
            //elseif ( floatval($row['hmd_data']) < 30.0 )
            elseif ( floatval($row['hmd_data']) < floatval($row['humid_min']) )
            {
                $recommendation = "Install Humidifier, Possible Health Issue and may cause frozen coil";
            }
            
            $arrHumidity = array(
                'message' => strval('Success'),
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'hmd_data' => floatval($row['hmd_data']),
                'record_time' => strval($row['record_time']),
                'recommendation' => strval($recommendation),
            );         
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {

                $tempUserAlarmParam = (floatval($row['temperature']));
                $tempThresholdValue = floatval($row['temp_data']) + $tempUserAlarmParam;

                //if ( ( floatval($row['hmd_data']) > 60.0 ) && 
                //if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
                //( floatval($row['temp_data']) > $tempUserAlarmParam ) )

                //if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
                //( floatval($row['temp_data']) > $tempThresholdValue ) )
                if ( floatval($row['hmd_data']) > floatval($row['humid_max']) )
                {
                    $recommendation = "Install Dehumidifier or else more strain in AC unit and High Current Consumption";
                }
                //elseif ( floatval($row['hmd_data']) < 30.0 )
                elseif ( floatval($row['hmd_data']) < floatval($row['humid_min']) )
                {
                    $recommendation = "Install Humidifier, Possible Health Issue and may cause frozen coil";
                }

                $arrHumidity[] = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'hmd_data' => floatval($row['hmd_data']),
                    'record_time' => strval($row['record_time']),
                    'recommendation' => strval($recommendation),
                );  
            }
        } 
        else 
        {
            return '0 result(s)';
            //return $arrCurrent = array('message' => 'Error');
        }

        $this->connection->getMysqliDB()->close();

        return $arrHumidity;
    }    

    public function humidityAndTemperatureAlarmHistoryQuery($deviceId = null, $limit = null) 
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

        $arrHumidity = array();
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

            //if ( ( floatval($row['hmd_data']) > 60.0 ) && 
            //if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
            //( floatval($row['temp_data']) > $tempUserAlarmParam ) )
            if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
            ( floatval($row['temp_data']) > $tempThresholdValue ) )
            {
                $recommendation = "Install Dehumidifier or else more strain in AC unit and High Current Consumption";
            } 
            elseif ( floatval($row['hmd_data']) < floatval($row['humid_min']) )
            {
                $recommendation = "Install Humidifier, Possible Health Issue and may cause frozen coil";
            }

            $arrHumidity = array(
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
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {

                $tempUserAlarmParam = (floatval($row['temperature']));
                $tempThresholdValue = floatval($row['temp_data']) + $tempUserAlarmParam;

                //$tempUserAlarmParam = (floatval($row['temperature']) + 0.5);
                //$tempUserAlarmParam = (floatval($row['temperature']));

                //if ( ( floatval($row['hmd_data']) > 60.0 ) && 
                if ( ( floatval($row['hmd_data']) > floatval($row['humid_max']) ) && 
                ( floatval($row['temp_data']) > $tempUserAlarmParam ) )
                {
                    
                    $recommendation = "Install Dehumidifier or els more strain in AC unit and High Current Consumption";
                }
                elseif ( floatval($row['hmd_data']) < floatval($row['humid_min']) )
                {
                    $recommendation = "Install Humidifier, Possible Health Issue and may cause frozen coil";
                }

                $arrHumidity[] = array(
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
        else 
        {
            return '0 result(s)';
            //return $arrCurrent = array('message' => 'Error');
        }

        $this->connection->getMysqliDB()->close();

        return $arrHumidity;
    }    
}