<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class ConfigModel
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
     
    public function configQuery($configId, $ipAddress) 
    {
        
        $sql = 'SELECT * FROM `config`';
        //$sql .= ' WHERE device_id = ' . intval($configId);
        $sql .= ' ORDER BY `config_id` DESC';
        $sql .=  ' LIMIT 1';
        
        $result = $this->connection->getMysqliDB()->query($sql);
        $row_count =  $result->num_rows;

        $arrConfig = array();
        
        if ($row_count == 1) 
        {
            $arrConfig = array();

            $sql = 'UPDATE `config`';
            $sql .= ' SET `ip_address` = ' . trim($ipAddress);
            $sql .= ' WHERE `config_id` = ' . trim($configId); 

            if ($this->connection->getMysqliDB()->query($sql) === TRUE)
            {
                $insert_id = $this->connection->getMysqliDB()->insert_id;

                $arrConfig = array(
                    'message' => 'Success',
                    'config_id' => $insert_id,
                );

            } else {

                $arrConfig = array('message' => 'Error',
                    'config_id' => null,
                );
            }
        }
        else
        {
            
            $sql = 'INSERT INTO `config` (`ip_address`)';
            $sql .= ' VALUES (' . $ipAddress . ')';

            if ($this->connection->getMysqliDB()->query($sql) === TRUE)
            {
                $insert_id = $this->connection->getMysqliDB()->insert_id;

                $arrConfig = array(
                    'message' => 'Success',
                    'config_id' => $insert_id,
                );
                

            } else {
                $arrConfig = array(
                    'message' => 'Error',
                    'config_id' => null,
                    );
            }
        }

        return $arrConfig;
    }


    public function configReadQuery($configId = null) 
    {
        $sql = 'SELECT * FROM `config`';
        $sql .= ' WHERE config_id = ' . intval($configId);
        $sql .= ' ORDER BY `config_id` DESC';
        $sql .=  ' LIMIT 1';
        
        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrConfig = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrConfig = array(
                'message' => 'Update',
                'config_id' => intval($row['config_id']),
                'ip_address' => floatval($row['ip_address']),
            );
        }
        else 
        {
            $arrConfig = array(
                'message' => 'Insert',
                'config_id' => null,
                'ip_address' => null,
            );
        }

        $this->connection->getMysqliDB()->close();

        return $arrConfig;
    }

    public function configUpdateQuery($configId, $ipAddress) 
    {
        $arrConfig = array();
        $sql = 'UPDATE `config`';
        $sql .= ' SET `temperature` = ' . trim($ipAddress);
        $sql .= ' WHERE `config_id` = ' . trim($configId); 

        if ($this->connection->getMysqliDB()->query($sql) === TRUE)
        {
            $insert_id = $this->connection->getMysqliDB()->insert_id;
            $arrConfig = array(
                'message' => 'Success',
                'config_id' => $insert_id,
            );

        } else {
            $arrConfig = array('message' => 'Error',
                'config_id' => null,
            );
        }

        return $arrConfig;
    }
}