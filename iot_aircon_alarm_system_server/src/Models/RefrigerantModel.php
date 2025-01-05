<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class RefrigerantModel 
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
     
    public function refrigerantQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT `record_id`, `device_id`, `vout_data`, `vref_data`, `vout_status`, `vref_status`, `alarm_status`, `record_time` FROM `data_gas`'; 
        
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

        $arrRefrigerant = array();
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

            //$arrRefrigerant['data'] = array(
            $arrRefrigerant = array(
                'message' => strval('Success'),
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
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
                if (floatval($row['vout_data']) >= floatval($row['vref_data']))
                {
                    $recommendation = 'Check for Refrigerant Leakage';
                }
                else 
                {
                    $recommendation = 'Normal';
                }

                $arrRefrigerant[] = array(
                    'message' => strval('Success'),
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
        } 
        else 
        {
            return '0 result(s)';
            //return $arrCurrent = array('message' => 'Error');
        }

        $this->connection->getMysqliDB()->close();

        return $arrRefrigerant;
    }

    public function refrigerantAlarmHistoryQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT dtg1.*, alrmparam1.* FROM `data_gas` dtg1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dtg1.`device_id` = alrmparam1.`device_id`'; 


        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dtg1.`device_id` = ' . intval($deviceId);          
        }

        $sql .= ' AND dtg1.`vout_data` >= dtg1.`vref_data`';

        $sql .= ' ORDER BY dtg1.`record_time` DESC';

        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }

        //echo $sql . "\n\n";
        
        $result =  $this->connection->getMysqliDB()->query($sql);

        //echo $result->num_rows . "\n\n";

        $arrRefrigerant = array();
        $recommendation = '';
        
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            //if (floatval($row['vout_data']) >= floatval($row['vref_data']))
            //{
                $recommendation = "Check for Refrigerant Leakage";

                $arrRefrigerant = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'vout_data' => floatval($row['vout_data']),
                    'vref_data' => floatval($row['vref_data']),
                    'vout_status' => strval($row['vout_status']),
                    'vref_status' => strval($row['vref_status']),
                    'alarm_status' => strval($row['alarm_status']),
                    'record_time' => strval($row['record_time']),
                    'refrigerant' => strval(floatval($row['vout_data']) . ' >= ' . floatval($row['vref_data'])),
                    'recommendation' => strval($recommendation),
                );

            //}
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
                //if (floatval($row['vout_data']) >= floatval($row['vref_data']))
                //{
                    $recommendation = "Check for Refrigerant Leakage";

                    $arrRefrigerant[] = array(
                        'message' => strval('Success'),
                        'record_id' => intval($row['record_id']),
                        'device_id' => intval($row['device_id']),
                        'vout_data' => floatval($row['vout_data']),
                        'vref_data' => floatval($row['vref_data']),
                        'vout_status' => strval($row['vout_status']),
                        'vref_status' => strval($row['vref_status']),
                        'alarm_status' => strval($row['alarm_status']),
                        'record_time' => strval($row['record_time']),
                        'refrigerant' => strval(floatval($row['vout_data']) . ' >= ' . floatval($row['vref_data'])),
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

        return $arrRefrigerant;
    }
}