<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class DeviceModel 
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
     
    public function deviceQuery($limit) 
    {
        $sql = 'SELECT DISTINCT `device_id`, `name`, `type`, `description`, `create_date`, `update_date` FROM `devices_list` ORDER BY `device_id` ASC';
        
        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrDevice = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrDevice = array(
                'device_id' => strval($row['device_id']),
                'name' => strval($row['name']),
                'type' => strval($row['type']),
                'description' => strval($row['description']),
                'create_date' => strval($row['create_date']),
                'update_date' => strval($row['update_date']),
            );
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
                $arrDevice[] = array(
                    'device_id' => strval($row['device_id']),
                    'name' => strval($row['name']),
                    'type' => strval($row['type']),
                    'description' => strval($row['description']),
                    'create_date' => strval($row['create_date']),
                    'update_date' => strval($row['update_date']),
                );
            }
        } 
        else 
        {
            return '0 result(s)';
        }

        $this->connection->getMysqliDB()->close();

        return $arrDevice;
    }

    public function deviceById($deviceId = null) 
    {
        $sql = 'SELECT DISTINCT `device_id`, `name`, `type`, `description`, `create_date`, `update_date` FROM `devices_list`';
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE `device_id` = ' . intval($deviceId);        
        }
        
        $sql .= ' ORDER BY `record_id` DESC';
        $sql .=  ' LIMIT 1';


        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrDevice = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrDevice = array(
                'device_id' => strval($row['device_id']),
                'name' => strval($row['name']),
                'type' => strval($row['type']),
                'description' => strval($row['description']),
                'create_date' => strval($row['create_date']),
                'update_date' => strval($row['update_date']),
            );
        }
        else 
        {
            return '0 result(s)';
        }
        return $arrDevice;
    }
}