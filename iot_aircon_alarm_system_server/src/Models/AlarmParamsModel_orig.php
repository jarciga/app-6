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
     
    public function alarmParamsQuery($name, $deviceId, $userId, $temperature, $current)
    {
        
        $sql = 'SELECT * FROM `alarm_parameters`';
        $sql .= ' WHERE device_id = ' . intval($deviceId);
        $sql .= ' ORDER BY `alarm_id` DESC';
        
        $result = $this->connection->getMysqliDB()->query($sql);
        $row_count =  $result->num_rows;

        $arrAlarmParams = array();
        
        if ($row_count == 1) 
        {
           $arrAlarmParams = array('message' => 'Update');
        }
        else
        {
            
            $sql = 'INSERT INTO `alarm_parameters` (`name`, `device_id`, `user_id`, `temperature`, `current`)';
            $sql .= ' VALUES ("' . 'Alarm_'.mt_rand() . '"' . ', ' . $deviceId . ', ' . $userId . ', ' . $temperature . ', ' . $current . ')';

            if ($this->connection->getMysqliDB()->query($sql) === TRUE)
            {
                $insert_id = $this->connection->getMysqliDB()->insert_id;
                $arrAlarmParams = array('message' => 'Success');
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
                'message' => 'Success',
                'alarm_id' => intval($row['alarm_id']),
                'name' => strval($row['name']),
                'device_id' => intval($row['device_id']),
                'user_id' => intval($row['user_id']),
                'temperature' => doubleval($row['temperature']),
                'current' => doubleval($row['current']),
            );
        }
        else 
        {
            $arrAlarmParams = array('message' => 'Error');
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
                $arrAlarmParams = array('message' => 'Success');
            } else {
                $arrAlarmParams = array('message' => 'Error');
            }
            return $arrAlarmParams;
    }
}