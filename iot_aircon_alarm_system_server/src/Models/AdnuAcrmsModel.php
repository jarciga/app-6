<?php

namespace AdnuAcrms\Models;

Class AdnuAcrmsModel 
{
    protected $dbConn = null;

    public function __construct($dbConn) 
    {
        $this->dbConn = $dbConn;
    }

    protected function getDBConnection()
    {
        return $this->dbConn;
    }

    public function testQuery() 
    {
        $sql = 'SELECT * FROM `data_temp_hmd` LIMIT 10;';
        $result = $this->getDBConnection()->query($sql);
        
        if ($result->num_rows > 0) 
        {
            // output data of each row
            $arrTempHmd = array();
            while($row = $result->fetch_assoc()) 
            {
                $arrTempHmd[] = array(
                    'record_id' => $row['record_id'],
                    'device_id' => $row['device_id'],
                    'temp_data' => $row['temp_data'],
                    'hmd_data' => $row['hmd_data'],
                    'record_time' => $row['record_time'],
                );
            }
        } 
        else 
        {
            echo "0 results";
        }

        $this->getDBConnection()->close();

        return $arrTempHmd;
    }
}