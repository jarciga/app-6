<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class VibrationModel 
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
     
    public function vibrationQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT dtvibr1.*, alrmparam1.* FROM `data_vibration` dtvibr1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtvibr1.device_id = alrmparam1.device_id'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtvibr1.`device_id` = ' . intval($deviceId);        
        }

        $sql .= ' ORDER BY dtvibr1.`record_time` DESC';

        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }

        
        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrVibration = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            //if (floatval($row['r_data']) > 22.0)
            if (floatval($row['r_data']) > floatval($row['vibration']))
            {
                $recommendation = 'Check for faulty compressor or any loose screws';
            }
            else 
            {
                $recommendation = 'Normal';
            }

            $arrVibration = array(
                'message' => strval('Success'),
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'r_data' => floatval($row['r_data']),
                'record_time' => strval($row['record_time']),
                'recommendation' => strval($recommendation),
            );
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
            //if (floatval($row['r_data']) > 22.0)
            if (floatval($row['r_data']) > floatval($row['vibration']))
                {
                    $recommendation = 'Check for faulty compressor or any loose screws';
                }
                else 
                {
                    $recommendation = 'Normal';
                }

                $arrVibration[] = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'r_data' => floatval($row['r_data']),
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

        return $arrVibration;
    }

    public function vibrationAlarmHistoryQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT dtvibr1.*, alrmparam1.* FROM `data_vibration` dtvibr1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtvibr1.`device_id` = alrmparam1.`device_id`';
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtvibr1.`device_id` = ' . intval($deviceId);        
        }

        $sql .= ' AND dtvibr1.`r_data` > alrmparam1.`vibration`';

        $sql .= ' ORDER BY dtvibr1.`record_time` DESC';

        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }

        //echo $sql . "\n\n";
        
        $result =  $this->connection->getMysqliDB()->query($sql);

        //echo $result->num_rows . "\n\n";

        $arrVibration = array();
        $recommendation = '';
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            ////if (floatval($row['r_data']) > 22.0)
            //if (floatval($row['r_data']) > floatval($row['vibration']))
            //{
                $recommendation = 'Check for faulty compressor or any loose screws';

                $arrVibration = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'r_data' => floatval($row['r_data']),
                    'record_time' => strval($row['record_time']),
                    'recommendation' => strval($recommendation),
                );
            //}

        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
                ////if (floatval($row['r_data']) > 22.0)
                //if (floatval($row['r_data']) > floatval($row['vibration']))
                //{
                    $recommendation = 'Check for faulty compressor or any loose screws';

                    $arrVibration[] = array(
                        'message' => strval('Success'),
                        'record_id' => intval($row['record_id']),
                        'device_id' => intval($row['device_id']),
                        'r_data' => floatval($row['r_data']),
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

        return $arrVibration;
    }
}