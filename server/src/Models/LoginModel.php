<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class LoginModel
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
     
    public function loginQuery($username, $password) 
    {
        
        $sql = 'SELECT * FROM `user_credentials`';
        $sql .= ' WHERE username = "' . trim($username) . '"';
        $sql .= ' AND password = "' . trim($password) . '"';
        
        $result = $this->connection->getMysqliDB()->query($sql);
        $row_count =  $result->num_rows;

        $arrLogin = array();
        
        if ($row_count == 1) 
        {

            $row = $result->fetch_assoc();

            $arrLogin = array(
                            'message' => 'Success', 
                            'user_id' => intval($row['user_id']),
                            'username' => strval($row['username']),
                            'group_id' => intval($row['group_id']),
                        );
            
        }
        else
        {
            $arrLogin = array('message' => 'The username or password you entered is not valid.');
        }

        return $arrLogin;
    }
}