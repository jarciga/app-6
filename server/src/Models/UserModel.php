<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class UserModel
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
     
    public function userQuery($userId, $userName, $password, $email, $groupId, $firstName, $middleName, $lastName) 
    {
        if ($userId == null)
        {
            return $arrUser = array(
                'message' => 'Error',
                'user_id' => null,
                );
            
        }

        if ($groupId == 'Admin') {
            $userGroupId == 1; //Admin
        } else {
            $userGroupId == 2; //User
        }

        $sql = 'SELECT DISTINCT usr_creds.*, usr_dt.* FROM `user_credentials` AS usr_creds';
        $sql .= ' INNER JOIN `user_data` AS usr_dt ON usr_creds.user_id = usr_dt.user_id';
        $sql .= ' WHERE `usr_creds`.user_id = ' . intval($userId);
        $sql .= ' ORDER BY `usr_creds`.create_date DESC';
        
        $result = $this->connection->getMysqliDB()->query($sql);
        $row_count =  $result->num_rows;

        $arrUser = array();
        
        if ($row_count == 1) 
        {
            
            $arrUser = array();

            $sqlUserCredentialsUpdate = 'UPDATE `user_credentials`';
            //$sqlUserCredentialsUpdate .= ' SET `user_id` = ' . trim($userId);
            $sqlUserCredentialsUpdate .= ' SET `username` = ' . trim($userName);
            $sqlUserCredentialsUpdate .= ', `password` = ' . trim($password);
            $sqlUserCredentialsUpdate .= ', `email` = ' . trim($email);
            $sqlUserCredentialsUpdate .= ', `group_id` = ' . trim($userGroupId);
            $sqlUserCredentialsUpdate .= ' WHERE `user_id` = ' . trim($userId); 

            $userCredentialsUpdateResult = $this->connection->getMysqliDB()->query($sqlUserCredentialsUpdate);

            if ($userCredentialsUpdateResult === TRUE)
            {
                $userCredentialsUpdateInsertId = $this->connection->getMysqliDB()->insert_id;

                $sqluserDataUpdate = 'UPDATE `user_data`';
                //$sqluserDataUpdate .= ' SET `user_id` = ' . trim($userId);
                $sqluserDataUpdate .= ' SET `first_name` = ' . trim($firstName);
                $sqluserDataUpdate .= ', `middle_name` = ' . trim($middleName);
                $sqluserDataUpdate .= ', `last_name` = ' . trim($lastName);
                $sqluserDataUpdate .= ' WHERE `user_id` = ' . $userCredentialsUpdateInsertId; // Or $userId

                $userDataUpdateResult = $this->connection->getMysqliDB()->query($sqluserDataUpdate);

                if ($userDataUpdateResult === TRUE)
                {
                    echo $userDataUpdateInsertId = $this->connection->getMysqliDB()->insert_id;

                    $arrUser = array(
                        'message' => 'Success',
                        'user_id' => $userDataUpdateInsertId,
                    );

                } else {
                    $arrUser = array('message' => 'Error',
                        'user_id' => null,
                    );
                }

            } else {
                $arrUser = array('message' => 'Error',
                    'user_id' => null,
                );
            }
        }
        else
        {
            $sqlUserCredentialsInsert = 'INSERT INTO `user_credentials` (`username`, `password`, `email`, `group_id`)';
            $sqlUserCredentialsInsert .= ' VALUES (' . $userName . ', ' . $password . ', ' . $email . ', ' . $groupId . ')';

            $sqlUserCredentialsInsertResult = $this->connection->getMysqliDB()->query($sqlUserCredentialsInsert);

            if ($sqlUserCredentialsInsertResult === TRUE)
            {
                $sqlUserCredentialsInsertInsertId = $this->connection->getMysqliDB()->insert_id;
                $sqluserDataInsert = 'INSERT INTO `user_data` (`first_name`, `middle_name`, `last_name`)';
                $sqluserDataInsert .= ' VALUES (' . $firstName . ', ' . $middleName . ', ' . $lastName . ')';

                $sqluserDataInsertResult = $this->connection->getMysqliDB()->query($sqluserDataInsert);

                if ($sqluserDataInsertResult === TRUE)
                {
                    $sqluserDataInsertInsertId = $this->connection->getMysqliDB()->insert_id;
                    
                    $arrUser = array(
                        'message' => 'Success',
                        'user_id' => $sqluserDataInsertInsertId,
                    );

                } else {
                    $arrUser = array(
                        'message' => 'Error',
                        'user_id' => null,
                        ); 
                }

            } else {
                $arrUser = array(
                    'message' => 'Error',
                    'user_id' => null,
                    );
            }

        }

        return $arrUser;
    }

    public function userReadQuery($userId = null) 
    {

        if ($userId == null)
        {
            return $arrUser = array(
                'message' => 'Insert',
                'user_id' => null,
                'username' => null,
                'password' => null,
                'email' => null,
                'group_id' => null,
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
            );
            
        }

        $sql = 'SELECT DISTINCT usr_creds.*, usr_dt.* FROM `user_credentials` AS usr_creds';
        $sql .= ' INNER JOIN `user_data` AS usr_dt ON usr_creds.user_id = usr_dt.user_id';
        $sql .= ' WHERE `usr_creds`.user_id = ' . intval($userId);
        $sql .= ' ORDER BY `usr_creds`.create_date DESC';
        
        $result =  $this->connection->getMysqliDB()->query($sql);

        $arrUser = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrUser = array(
                'message' => 'Update',
                'user_id' => intval($row['user_id']),
                'username' => strval($row['username']),
                'password' => intval($row['password']),
                'email' => intval($row['email']),
                'group_id' => intval($row['group_id']),
                'first_name' => doubleval($row['first_name']),
                'middle_name' => doubleval($row['middle_name']),
                'last_name' => doubleval($row['last_name']),
            );
        }
        else 
        {
            $arrUser = array(
                'message' => 'Insert',
                'user_id' => null,
                'username' => null,
                'password' => null,
                'email' => null,
                'group_id' => null,
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
            );
        }

        $this->connection->getMysqliDB()->close();

        return $arrUser;
    }

    public function userCreateQuery($userName, $password, $email, $groupId, $firstName, $middleName, $lastName) 
    {
        $sqlUserCredentialsInsert = 'INSERT INTO `user_credentials` (`username`, `password`, `email`, `group_id`)';
        $sqlUserCredentialsInsert .= ' VALUES ("' . trim($userName) . '", "' . trim($password) . '", "' . trim($email) . '", ' . intval($groupId) . ')';

        $userCredentialsInsertResult = $this->connection->getMysqliDB()->query($sqlUserCredentialsInsert);

        if ($userCredentialsInsertResult === TRUE)
        {
            $userCredentialsInsertInsertId = $this->connection->getMysqliDB()->insert_id;
            $sqluserDataInsert = 'INSERT INTO `user_data` (`user_id`, `first_name`, `middle_name`, `last_name`)';
            $sqluserDataInsert .= ' VALUES (' . $userCredentialsInsertInsertId . ', "' . trim(ucwords($firstName)) . '", "' . trim(ucwords($middleName)) . '", "' . trim(ucwords($lastName)) . '")';

            $userDataInsertResult = $this->connection->getMysqliDB()->query($sqluserDataInsert);

            if ($userDataInsertResult === TRUE)
            {
                $userDataInsertInsertId = $this->connection->getMysqliDB()->insert_id;
              
                $sqlUserInfo = 'SELECT DISTINCT usr_creds.*, usr_dt.* FROM `user_credentials` AS usr_creds';
                $sqlUserInfo .= ' INNER JOIN `user_data` AS usr_dt ON usr_creds.user_id = usr_dt.user_id';
                $sqlUserInfo .= ' WHERE `usr_creds`.user_id = ' . intval($userCredentialsInsertInsertId);
                $sqlUserInfo .= ' ORDER BY `usr_creds`.create_date DESC';

                $userInfoResult =  $this->connection->getMysqliDB()->query($sqlUserInfo);

                if ($userInfoResult->num_rows == 1) 
                {   
                    $userInfoRow = $userInfoResult->fetch_assoc();

                    $arrUser = array(
                        'message' => 'Success',
                        'user_id' => $userCredentialsInsertInsertId,
                        'username' => strval($userInfoRow['username']),
                        'password' => strval($userInfoRow['password']),
                        'email' =>  strval($userInfoRow['email']),
                        'first_name' =>  strval($userInfoRow['first_name']),
                        'middle_name' =>  strval($userInfoRow['middle_name']),
                        'last_name' =>  strval($userInfoRow['last_name']),
                    );
                } else {
                    $arrUser = array(
                        'message' => 'Error',
                        'user_id' => null,
                        ); 
                }

            } else {
                $arrUser = array(
                    'message' => 'Error',
                    'user_id' => null,
                    ); 
            }

        } else {
            $arrUser = array(
                'message' => 'Error',
                'user_id' => null,
                );
        }

        return $arrUser; 
    }

    public function userUpdateQuery($userId, $userName, $password, $email, $groupId, $firstName, $middleName, $lastName) 
    {
        $arrUser = array();

        $sqlUserCredentialsUpdate = 'UPDATE `user_credentials`';
        //$sqlUserCredentialsUpdate .= ' SET `user_id` = ' . trim($userId);
        $sqlUserCredentialsUpdate .= ' SET `username` = ' . trim($userName);
        $sqlUserCredentialsUpdate .= ', `password` = ' . trim($password);
        $sqlUserCredentialsUpdate .= ', `email` = ' . trim($email);
        $sqlUserCredentialsUpdate .= ', `group_id` = ' . trim($groupId);
        $sqlUserCredentialsUpdate .= ' WHERE `user_id` = ' . trim($userId); 

        $userCredentialsUpdateResult = $this->connection->getMysqliDB()->query($sqlUserCredentialsUpdate);

        if ($userCredentialsUpdateResult === TRUE)
        {
            $userCredentialsUpdateInsertId = $this->connection->getMysqliDB()->insert_id;

            $sqluserDataUpdate = 'UPDATE `user_data`';
            //$sqluserDataUpdate .= ' SET `user_id` = ' . trim($userId);
            $sqluserDataUpdate .= ' SET `first_name` = ' . trim($firstName);
            $sqluserDataUpdate .= ', `middle_name` = ' . trim($middleName);
            $sqluserDataUpdate .= ', `last_name` = ' . trim($lastName);
            $sqluserDataUpdate .= ' WHERE `user_id` = ' . $userCredentialsUpdateInsertId; // Or $userId

            $userDataUpdateResult = $this->connection->getMysqliDB()->query($sqluserDataUpdate);

            if ($userDataUpdateResult === TRUE)
            {
                $userDataUpdateInsertId = $this->connection->getMysqliDB()->insert_id;

                $arrUser = array(
                    'message' => 'Success',
                    'user_id' => $userDataUpdateInsertId,
                );

            } else {
                $arrUser = array('message' => 'Error',
                    'user_id' => null,
                );
            }

        } else {
            $arrUser = array('message' => 'Error',
                'user_id' => null,
            );
        }

        return $arrUser;
    }


    public function userGroupIdById($groupId)
    {
        $sql = 'SELECT DISTINCT * FROM `user_groups`';
        $sql .= ' WHERE `group_id` = ' . $groupId;
        $sql .= ' LIMIT 1';

        $result =  $this->connection->getMysqliDB()->query($sql);
        $row = $result->fetch_assoc();
        $userGroupId = $row['group_id'];

        return $userGroupId;
        
    }
}