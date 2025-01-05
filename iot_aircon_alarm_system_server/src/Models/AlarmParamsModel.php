<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class AlarmParamsModel
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
     
    public function alarmParamsQuery($name = null, $deviceId, $userId, $temperature, $current) 
    {
        
        $sql = 'SELECT * FROM `alarm_parameters`';
        $sql .= ' WHERE device_id = ' . intval($deviceId);
        $sql .= ' ORDER BY `alarm_id` DESC';
        
        $result = $this->connection->getMysqliDB()->query($sql);
        $row_count =  $result->num_rows;

        $arrAlarmParams = array();
        
        if ($row_count == 1) 
        {
            $arrAlarmParams = array();

            $sql = 'UPDATE `alarm_parameters`';
            $sql .= ' SET `user_id` = ' . trim($userId);
            $sql .= ', `temperature` = ' . trim($temperature);
            $sql .= ', `current` = ' . trim($current);
            $sql .= ' WHERE `device_id` = ' . trim($deviceId); 

            if ($this->connection->getMysqliDB()->query($sql) === TRUE)
            {
                $insert_id = $this->connection->getMysqliDB()->insert_id;

                $arrAlarmParams = array(
                    'message' => 'Success',
                    'alarm_id' => $insert_id,
                );

            } else {

                $arrAlarmParams = array('message' => 'Error',
                    'alarm_id' => null,
                );
            }
        }
        else
        {
            
            $sql = 'INSERT INTO `alarm_parameters` (`name`, `device_id`, `user_id`, `temperature`, `current`)';
            $sql .= ' VALUES ("' . 'Alarm_'.mt_rand() . '"' . ', ' . $deviceId . ', ' . $userId . ', ' . $temperature . ', ' . $current . ')';

            if ($this->connection->getMysqliDB()->query($sql) === TRUE)
            {
                $insert_id = $this->connection->getMysqliDB()->insert_id;

                $arrAlarmParams = array(
                    'message' => 'Success',
                    'alarm_id' => $insert_id,
                );
                

            } else {
                $arrAlarmParams = array(
                    'message' => 'Error',
                    'alarm_id' => null,
                    );
            }
        }

        return $arrAlarmParams;
    }


    public function alarmParamsReadQuery($deviceId = null) 
    {
        $sql = 'SELECT * FROM `alarm_parameters`';
        $sql .= ' WHERE device_id = ' . intval($deviceId);
        $sql .= ' ORDER BY `alarm_id` DESC';
        
        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrAlarmParams = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrAlarmParams = array(
                'message' => 'Update',
                'alarm_id' => intval($row['alarm_id']),
                'name' => strval($row['name']),
                'device_id' => intval($row['device_id']),
                'user_id' => intval($row['user_id']),
                'temperature' => floatval($row['temperature']),
                'current' => floatval($row['current']),
            );
        }
        else 
        {
            $arrAlarmParams = array(
                'message' => 'Insert',
                'alarm_id' => null,
                'name' => null,
                'device_id' => null,
                'user_id' => null,
                'temperature' => null,
                'current' => null,
            );
        }

        $this->connection->getMysqliDB()->close();

        return $arrAlarmParams;
    }

    public function alarmParamsUpdateQuery($deviceId, $userId, $temperature, $current) 
    {
        $arrAlarmParams = array();
        $sql = 'UPDATE `alarm_parameters`';
        $sql .= ' SET `user_id` = ' . trim($userId);
        $sql .= ', `temperature` = ' . trim($temperature);
        $sql .= ', `current` = ' . trim($current);
        $sql .= ' WHERE `device_id` = ' . trim($deviceId); 

        if ($this->connection->getMysqliDB()->query($sql) === TRUE)
        {
            $insert_id = $this->connection->getMysqliDB()->insert_id;
            $arrAlarmParams = array(
                'message' => 'Success',
                'alarm_id' => $insert_id,
            );

        } else {
            $arrAlarmParams = array('message' => 'Error',
                'alarm_id' => null,
            );
        }

        return $arrAlarmParams;
    }
}