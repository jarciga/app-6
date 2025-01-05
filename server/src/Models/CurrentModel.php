<?php

namespace AdnuAcrms\Models;

use AdnuAcrms\Config\Connection;

Class CurrentModel 
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
     
    public function currentQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT `record_id`, `device_id`, `amp_data`, `record_time` FROM `data_amp`'; 
        
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

        $arrCurrent = array();
        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();
            $arrCurrent = array(
                'message' => strval('Success'),
                'record_id' => intval($row['record_id']),
                'device_id' => intval($row['device_id']),
                'amp_data' => floatval($row['amp_data']),
                'record_time' => strval($row['record_time']),
            );
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {
                $arrCurrent[] = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'amp_data' => floatval($row['amp_data']),
                    'record_time' => strval($row['record_time']),
                );
            }
        } 
        else 
        {
            return '0 result(s)';
            //return $arrCurrent = array('message' => 'Error');
        }

        $this->connection->getMysqliDB()->close();

        return $arrCurrent;
    }

    public function currentAlarmHistoryQuery($deviceId = null, $limit = null) 
    {
        $sql = 'SELECT dta1.*, alrmparam1.* FROM `data_amp` dta1';
        $sql .= ' INNER JOIN alarm_parameters alrmparam1 ON dta1.device_id = alrmparam1.device_id'; 
        
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sql .= ' WHERE dta1.`device_id` = ' . intval($deviceId);        
        }

        //$sql .= ' AND (dta1.amp_data > alrmparam1.current)';

        $sql .= ' AND (dta1.amp_data > (alrmparam1.current * alrmparam1.current_multiplier))';

        $sql .= ' ORDER BY dta1.`record_time` DESC';

        if (isset($limit) || !empty($limit))  
        {
            $sql .=  ' LIMIT ' . $limit;
        }

        //echo $sql . "\n\n";
        
        $result =  $this->connection->getMysqliDB()->query($sql);

        //echo $result->num_rows . "\n\n";

        $arrCurrent = array();
        $currenAlarmParam = array();
        $recommendation = '';
        $currentThresholdValuePlus = 0;
        $currentThresholdValueMinus = 0;

        if ($result->num_rows == 1) 
        {
            $row = $result->fetch_assoc();

            //$currentThresholdValuePlus = floatval($row['amp_data']) + (floatval($row['current']));
            //$currentThresholdValueMinus = floatval($row['amp_data']) - (floatval($row['current']));

            $currentThresholdValuePlus = (floatval($row['current']));
            $currentThresholdValueMinus = (floatval($row['current']));

            //$currenAlarmParam[0] = (floatval($row['current']) + 0.1);
            //$currenAlarmParamLess[1] = (floatval($row['current']) - 0.1);

            //$currenAlarmParam[0] = (floatval($row['current']));
            //$currenAlarmParamLess[1] = (floatval($row['current']));

            $currenAlarmParam[0] = $currentThresholdValuePlus;
            $currenAlarmParam[1] = $currentThresholdValueMinus;

            ////if ( ( floatval($row['amp_data']) > $currenAlarmParam[0] ) || 
            ////( floatval($row['amp_data']) < $currenAlarmParamLess[1] )  )

            //if ( ( floatval($row['amp_data']) > $currenAlarmParam[0] ) || 
            //( floatval($row['amp_data']) < $currenAlarmParam[1] )  )
            //{
                $recommendation = 'Check for the Electrical Wiring / Repeated Power Surge may cause damage to the system';
            
                //$arrCurrent['data'] = array(
                $arrCurrent = array(
                    'message' => strval('Success'),
                    'record_id' => intval($row['record_id']),
                    'device_id' => intval($row['device_id']),
                    'amp_data' => floatval($row['amp_data']),
                    'user_input_current' => floatval($row['current']),
                    'record_time' => strval($row['record_time']),
                    'recommendation' => strval($recommendation),
                ); 
            //}
        }
        elseif ($result->num_rows != 0 && $result->num_rows > 1) 
        {
            while($row = $result->fetch_assoc()) 
            {

                //$currentThresholdValuePlus = floatval($row['amp_data']) + (floatval($row['current']));
                //$currentThresholdValueMinus = floatval($row['amp_data']) - (floatval($row['current']));

                $currentThresholdValuePlus = (floatval($row['current']));
                $currentThresholdValueMinus = (floatval($row['current']));

                //$currenAlarmParam[0] = (floatval($row['current']) + 0.1);
                //$currenAlarmParamLess[1] = (floatval($row['current']) - 0.1);
                
                //$currenAlarmParam[0] = (floatval($row['current']));
                //$currenAlarmParamLess[1] = (floatval($row['current']));
                
                $currenAlarmParam[0] = $currentThresholdValuePlus;
                $currenAlarmParam[1] = $currentThresholdValueMinus;

                ////if ( ( floatval($row['amp_data']) > $currenAlarmParam[0] ) || 
                ////( floatval($row['amp_data']) < $currenAlarmParamLess[1] )  )
                
                //if ( ( floatval($row['amp_data']) > $currenAlarmParam[0] ) || 
                //( floatval($row['amp_data']) < $currenAlarmParam[1] )  )
                //{
                    $recommendation = 'Check for the Electrical Wiring / Repeated Power Surge may cause damage to the system';        

                    //$arrCurrent['data'][] = array(
                    $arrCurrent[] = array(
                        'message' => strval('Success'),
                        'record_id' => intval($row['record_id']),
                        'device_id' => intval($row['device_id']),
                        'amp_data' => floatval($row['amp_data']),
                        'user_input_current' => floatval($row['current']),
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

        return $arrCurrent;
    }

}