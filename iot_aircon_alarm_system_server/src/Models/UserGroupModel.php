<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class UserGroupModel 
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
     
    public function userGroupQuery($limit) 
    {
        $sql = 'SELECT DISTINCT `group_id`, `name`, `description` FROM `user_groups` ORDER BY `group_id` ASC';
        
        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }

        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrUserGroup = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrUserGroup = array(
                'group_id' => strval($row['group_id']),
                'name' => strval($row['name']),
                'description' => strval($row['description']),
            );
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
                $arrUserGroup[] = array(
                    'group_id' => strval($row['group_id']),
                    'name' => strval($row['name']),
                    'description' => strval($row['description']),
                );
            }
        } 
        else 
        {
            return '0 result(s)';
        }

        $this->connection->getMysqliDB()->close();

        return $arrUserGroup;
    }

    public function userGroudById($groupId = null) 
    {
        $sql = 'SELECT DISTINCT `group_id`, `name`, `description` FROM `user_groups`';
        
        if (isset($groupId) || !empty($groupId)) 
        {
            $sql .= ' WHERE `group_id` = ' . intval($groupId);        
        }
        
        $sql .= ' ORDER BY `group_id` DESC';
        $sql .=  ' LIMIT 1';


        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrUserGroup = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrUserGroup = array(
                'group_id' => strval($row['group_id']),
                'name' => strval($row['name']),
                'description' => strval($row['description']),
            );
        }
        else 
        {
            return '0 result(s)';
        }

        //Don't close the database connection
        //$this->connection->getMysqliDB()->close();

        return $arrUserGroup;
    }
}