<?php

date_default_timezone_set('Asia/Taipei');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use AdnuAcrms\Config\Connection;

$appDirectory = __DIR__ . '/..';
$appPath = realpath(rtrim($appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR;

//require $appPath . 'vendor\autoload.php';
require $appPath . 'vendor/autoload.php';

$connection = new Connection();

//Get Devices List count
$devicesListSql = 'SELECT DISTINCT * FROM `devices_list`';
$devicesListResult =  $connection->getMysqliDB()->query($devicesListSql);

if ($devicesListResult->num_rows != 0 && $devicesListResult->num_rows > 1) 
{
    echo "<h1>Device/s:";
    while($deviceRow = $devicesListResult->fetch_assoc()) 
    {
        //Get Device Id
        
        $deviceId = $deviceRow['device_id'];
        echo '<br />';
        echo "<h3>Device " . $deviceId . "</h6>";
        echo '<br />';

        //Get Sensor notification data per device id
        $sensorNotificationSql = 'SELECT * FROM `sensor_notifications`';
        if (isset($deviceId) || !empty($deviceId)) 
        {
            $sensorNotificationSql .= ' WHERE `sensor_device_id` = ' . intval($deviceId);          
        }
        
        $sensorNotificationResult =  $connection->getMysqliDB()->query($sensorNotificationSql);
        //var_dump($sensorNotificationResult);

        $sensorValueCounter = 0;
        $errorMessage = array();
        $successMessage = array();
        while($sensorNotificationRow = $sensorNotificationResult->fetch_assoc()) 
        {
            $sensorNotification = new AdnuAcrms\Controllers\SensorNotificationController();
            $sensorDataLatestRefrigerant = $sensorNotification->sensorDataLatestRefrigerantOutput($deviceId);
            $sensorDataLatestHumidity = $sensorNotification->sensorDataLatestHumidityOutput($deviceId);
            $sensorDataLatestTemperature = $sensorNotification->sensorDataLatestTemperatureOutput($deviceId);
            $sensorDataLatestCurrent = $sensorNotification->sensorDataLatestCurrentOutput($deviceId);
            $sensorDataLatestVibration = $sensorNotification->sensorDataLatestVibrationOutput($deviceId);
            
           /* Debug
           if($deviceId == 2) { Device 2
                echo "<pre>";
                var_dump($sensorDataLatestRefrigerant);
                echo "</pre>";
                
                echo "<pre>";
                var_dump($sensorDataLatestHumidity);
                echo "</pre>";
    
                echo "<pre>";
                var_dump($sensorDataLatestTemperature);
                echo "</pre>";
    
                echo "<pre>";
                var_dump($sensorDataLatestCurrent);
                echo "</pre>";
    
                echo "<pre>";
                var_dump($sensorDataLatestVibration);
                echo "</pre>";
            }
           */ 

            //Sensor Name: Temperature
            if(trim(strtoupper($sensorNotificationRow['sensor_name'])) == trim($sensorDataLatestTemperature['sensor_name']))
            {
                //If sensor value equal to record_id - continue;
               
                
                if (intval($sensorNotificationRow['sensor_value']) == $sensorDataLatestTemperature['record_id'])
                {
                    // echo 'continue';
                    //continue;
                    $sensorValueCounter++;
                }
                

                //If sensor value not equal to record_id - perform check 
                //And then update sensor_value from sensor notification table
                echo 'Sensor Value(Temperature Record Id): ' . $sensorNotificationRow['sensor_value'];
                echo '<br />';
                echo 'Latest Temperature Record Id: ' . $sensorDataLatestTemperature['record_id'];

                
                $arrTemperature = array();
                $recommendation = '';
                $tempUserAlarmParam = 0;
                $tempThresholdValue = 0;

                //echo $tempUserAlarmParam = (floatval($sensorDataLatestTemperature['temperature']) + 0.5);
                echo $tempUserAlarmParam = (floatval($sensorDataLatestTemperature['temperature']));
                echo $tempThresholdValue = floatval($sensorDataLatestTemperature['temp_data']) + $tempUserAlarmParam;

                echo '<br />';
                echo '$tempThresholdValue: ' . $tempThresholdValue;
                echo '<br />';

                $arrTemperature = array(
                    'sensor_name' => strval('Temperature'),
                    'record_id' => intval($sensorDataLatestTemperature['record_id']),
                    'device_id' => intval($sensorDataLatestTemperature['device_id']),
                    'temp_data' => floatval($sensorDataLatestTemperature['temp_data']),
                    'user_input_temperature' => floatval($sensorDataLatestTemperature['temperature']),
                    'temp_threshold_value' => $tempThresholdValue,
                    'record_time' => strval($sensorDataLatestTemperature['record_time']),
                    'condition' => '(Temperature Data: ' . floatval($sensorDataLatestTemperature['temp_data']) . ' > ' . 'Temperature Alarm Param: ' . $tempUserAlarmParam . ')'
                    //'error_success_message' => ''
                    //'recommendation' => strval($recommendation),
                );

                if ( ( floatval($sensorDataLatestTemperature['temp_data']) > $tempUserAlarmParam ) )
                //if ( ( floatval($sensorDataLatestTemperature['temp_data']) > $tempThresholdValue ) )
                {
                    //echo $recommendation = "Frozen Coil: Check for a Frozen coil and\nthe airflow";
    
                    //$arrTemperature['data'] = array(
                    /*
                    $arrTemperature = array(
                        'sensor_name' => strval('Temperature'),
                        'record_id' => intval($sensorDataLatestTemperature['record_id']),
                        'device_id' => intval($sensorDataLatestTemperature['device_id']),
                        'temp_data' => floatval($sensorDataLatestTemperature['temp_data']),
                        'user_input_temperature' => floatval($sensorDataLatestTemperature['temperature']),
                        'record_time' => strval($sensorDataLatestTemperature['record_time']),
                        'recommendation' => strval($recommendation),
                    );
                    */

                    //$arrTemperature['recommendation'] = strval($recommendation);

                    $arrTemperature['error_success_message'] = 'Abnormal Reading';
                    $errorMessage[] = $arrTemperature;

                } else {

                    $arrTemperature['error_success_message'] = 'Normal Reading';
                    $successMessage[] = $arrTemperature;
                    
                }
                

                //$sensorNotificationTemperatureUpdateSql = 'UPDATE `sensor_notifications`';
                //$sensorNotificationTemperatureUpdateSql .= ' SET `sensor_value` = ' . strval($sensorDataLatestTemperature['record_id']);
                ////$sensorNotificationTemperatureUpdateSql .= ' WHERE `sensor_notifications`.`sensor_id` = ' . intval(3); //Temperature
                //$sensorNotificationTemperatureUpdateSql .= ' WHERE `sensor_notifications`.`sensor_device_id` = ' . $deviceId;
                //$sensorNotificationTemperatureUpdateSql .= ' AND `sensor_notifications`.`sensor_name` = "' . $sensorNotificationRow['sensor_name'] .'"';
                //$connection->getMysqliDB()->query($sensorNotificationTemperatureUpdateSql);

                $sensorNotificationTemperatureUpdateSql = 'UPDATE `sensor_notifications`';
                $sensorNotificationTemperatureUpdateSql .= ' SET `sensor_value` = ' . strval($sensorDataLatestTemperature['record_id']);
                //$sensorNotificationTemperatureUpdateSql .= ' WHERE `sensor_notifications`.`sensor_id` = ' . intval(3); //Temperature
                $sensorNotificationTemperatureUpdateSql .= ' WHERE `sensor_notifications`.`sensor_device_id` = ' . $deviceId;
                $sensorNotificationTemperatureUpdateSql .= ' AND `sensor_notifications`.`sensor_name` = "' . $sensorNotificationRow['sensor_name'] .'"';
                
                echo $sensorNotificationTemperatureUpdateSql;
                
                $connection->getMysqliDB()->query($sensorNotificationTemperatureUpdateSql);


            }

            //Sensor Name: Humidity
            if(trim(strtoupper($sensorNotificationRow['sensor_name'])) == trim($sensorDataLatestHumidity['sensor_name']))
            {
                //If sensor value equal to record_id - continue;
                
                if (intval($sensorNotificationRow['sensor_value']) == $sensorDataLatestHumidity['record_id'])
                {
                    // echo 'continue';
                    //continue;
                    $sensorValueCounter++;
                }
                

                //If sensor value not equal to record_id - perform check 
                //And then update sensor_value from sensor notification table
                echo 'Sensor Value(Temperature Record Id): ' . $sensorNotificationRow['sensor_value'];
                echo '<br />';
                echo 'Latest Temperature Record Id: ' . $sensorDataLatestHumidity['record_id'];

                
                $arrHumidity = array();
                $recommendation = '';
                $tempUserAlarmParam = 0;
                $tempThresholdValue = 0;

                //$tempUserAlarmParam = (floatval($sensorDataLatestHumidity['temperature']) + 0.5);
                $tempUserAlarmParam = (floatval($sensorDataLatestHumidity['temperature']));
                echo $tempThresholdValue = floatval($sensorDataLatestTemperature['temp_data']) + $tempUserAlarmParam;

                $arrHumidity = array(
                    'sensor_name' => strval('Humidity'),
                    'record_id' => intval($sensorDataLatestHumidity['record_id']),
                    'device_id' => intval($sensorDataLatestHumidity['device_id']),
                    'humid_temp_data' => floatval($sensorDataLatestHumidity['temp_data']),
                    'user_input_humidity_temperature' => floatval($sensorDataLatestHumidity['temperature']),
                    'hmd_data' => floatval($sensorDataLatestHumidity['hmd_data']),
                    'humid_max' => floatval($sensorDataLatestHumidity['humid_max']),
                    'humid_min' => floatval($sensorDataLatestHumidity['humid_min']),
                    'record_time' => strval($sensorDataLatestHumidity['record_time']),
                    'condition' => ''
                    //'error_success_message' => ''
                    //'recommendation' => strval($recommendation),
                );
    
                //if ( ( floatval($sensorDataLatestHumidity['hmd_data']) > 60.0 ) && 
                //if ( ( floatval($sensorDataLatestHumidity['hmd_data']) > floatval($sensorDataLatestHumidity['humid_max']) ) && 
                //( floatval($sensorDataLatestHumidity['temp_data']) > $tempUserAlarmParam ) )
                //if ( ( floatval($sensorDataLatestHumidity['hmd_data']) > floatval($sensorDataLatestHumidity['humid_max']) ) && 
                //( floatval($sensorDataLatestHumidity['temp_data']) > $tempThresholdValue ) )

                if ( ( floatval($sensorDataLatestHumidity['hmd_data']) > floatval($sensorDataLatestHumidity['humid_max']) ) )
                {
                    //echo $recommendation = "Install Dehumidifier or else\nmore strain in AC unit and\nHigh Current Consumption";

                    //$arrHumidity['data'] = array(
                    /*
                    $arrHumidity = array(
                        'sensor_name' => strval('Humidity'),
                        'record_id' => intval($sensorDataLatestHumidity['record_id']),
                        'device_id' => intval($sensorDataLatestHumidity['device_id']),
                        'temp_data' => intval($sensorDataLatestHumidity['temp_data']),
                        'user_input_temperature' => floatval($sensorDataLatestHumidity['temperature']),
                        'hmd_data' => intval($sensorDataLatestHumidity['hmd_data']),
                        'humid_max' => intval($sensorDataLatestHumidity['humid_max']),
                        'humid_min' => intval($sensorDataLatestHumidity['hmd_min']),
                        'record_time' => strval($sensorDataLatestHumidity['record_time']),
                        'recommendation' => strval($recommendation),
                    );
                    */

                    $arrHumidity['condition'] = '(Humidity Data: ' . floatval($sensorDataLatestHumidity['hmd_data']) . ' > ' . 'Humidity Max Alarm Param: ' . floatval($sensorDataLatestHumidity['humid_max']) . ') And (' . 'Temperature Data: ' . floatval($sensorDataLatestHumidity['temp_data']) . ' > ' . 'Temperature Alarm Param: ' . $tempUserAlarmParam . ')';
                    $arrHumidity['error_success_message'] = 'Abnormal Reading';
                    $errorMessage[] = $arrHumidity;

                }
                //elseif ( floatval($sensorDataLatestHumidity['hmd_data']) < 30.0 )
                elseif ( floatval($sensorDataLatestHumidity['hmd_data']) < floatval($sensorDataLatestHumidity['humid_min']) )
                {
                    //echo $recommendation = "Install Humidifier,\nPossible Health Issue and\nmay cause frozen coil";

                    //$arrHumidity['data'] = array(
                    /*
                    $arrHumidity = array(
                        'sensor_name' => strval('Humidity'),
                        'record_id' => intval($sensorDataLatestHumidity['record_id']),
                        'device_id' => intval($sensorDataLatestHumidity['device_id']),
                        'temp_data' => intval($sensorDataLatestHumidity['temp_data']),
                        'user_input_temperature' => floatval($sensorDataLatestHumidity['temperature']),
                        'hmd_data' => intval($sensorDataLatestHumidity['hmd_data']),
                        'humid_max' => intval($sensorDataLatestHumidity['humid_max']),
                        'humid_min' => intval($sensorDataLatestHumidity['hmd_min']),                
                        'record_time' => strval($sensorDataLatestHumidity['record_time']),
                        'recommendation' => strval($recommendation),
                    );
                    */

                    $arrHumidity['condition'] = '(Humidity Data: '. floatval($sensorDataLatestHumidity['hmd_data']) . ' < ' . 'Humidity Min: ' . floatval($sensorDataLatestHumidity['humid_min']) . ')';
                    $arrHumidity['error_success_message'] = 'Abnormal Reading';
                    $errorMessage[] = $arrHumidity;

                } else {
                    $arrHumidity['error_success_message'] = 'Normal Reading';
                    $successMessage[] = $arrHumidity;
                }

                $sensorNotificationHumidityUpdateSql = 'UPDATE `sensor_notifications`';
                $sensorNotificationHumidityUpdateSql .= ' SET `sensor_value` = ' . strval($sensorDataLatestHumidity['record_id']);
                //$sensorNotificationHumidityUpdateSql .= ' WHERE `sensor_notifications`.`sensor_id` = ' . intval(2); //Humidity
                $sensorNotificationHumidityUpdateSql .= ' WHERE `sensor_notifications`.`sensor_device_id` = ' . $deviceId;
                $sensorNotificationHumidityUpdateSql .= ' AND `sensor_notifications`.`sensor_name` = "' . $sensorNotificationRow['sensor_name'] .'"';
                $connection->getMysqliDB()->query($sensorNotificationHumidityUpdateSql);
            }

            //Sensor Name: Current
            if(trim(strtoupper($sensorNotificationRow['sensor_name'])) == trim($sensorDataLatestCurrent['sensor_name']))
            {
                //If sensor value equal to record_id - continue;
                
                if (intval($sensorNotificationRow['sensor_value']) == $sensorDataLatestCurrent['record_id'])
                {
                    // echo 'continue';
                    //continue;
                    $sensorValueCounter++;
                }
                

                //If sensor value not equal to record_id - perform check 
                //And then update sensor_value from sensor notification table
                echo 'Sensor Value(Temperature Record Id): ' . $sensorNotificationRow['sensor_value'];
                echo '<br />';
                echo 'Latest Temperature Record Id: ' . $sensorDataLatestCurrent['record_id'];

                $arrCurrent = array();
                $currenAlarmParam = array();
                $recommendation = '';
                $currentThresholdValuePlus = 0;
                $currentThresholdValueMinus = 0;

                //$currentThresholdValuePlus = floatval($sensorDataLatestCurrent['amp_data']) + (floatval($sensorDataLatestCurrent['current']));
                //$currentThresholdValueMinus = floatval($sensorDataLatestCurrent['amp_data']) - (floatval($sensorDataLatestCurrent['current']));

                $currentThresholdValuePlus = (floatval($sensorDataLatestCurrent['current']) * floatval($sensorDataLatestCurrent['current_multiplier']));
                $currentThresholdValueMinus = floatval($sensorDataLatestCurrent['current']);
                
                echo '<br/>';
                echo 'user_input_current: ' . floatval($sensorDataLatestCurrent['current']);
                echo '<br/>';

                echo '<br/>';
                echo 'currentThresholdValuePlus: ' . $currentThresholdValuePlus;
                echo '<br/>';

                echo '<br/>';
                echo 'currentThresholdValueMinus: ' . $currentThresholdValueMinus;
                echo '<br/>';

                //$currenAlarmParam[0] = (floatval($sensorDataLatestCurrent['current']) + 0.1);
                //$currenAlarmParam[1] = (floatval($sensorDataLatestCurrent['current']) - 0.1);

                //$currenAlarmParam[0] = (floatval($sensorDataLatestCurrent['current']));
                //$currenAlarmParam[1] = (floatval($sensorDataLatestCurrent['current'])); 

                $currenAlarmParam[0] = $currentThresholdValuePlus;
                $currenAlarmParam[1] = $currentThresholdValueMinus; 

                $arrCurrent = array(
                    'sensor_name' => strval('Current'),
                    'record_id' => intval($sensorDataLatestCurrent['record_id']),
                    'device_id' => intval($sensorDataLatestCurrent['device_id']),
                    'amp_data' => floatval($sensorDataLatestCurrent['amp_data']),
                    'user_input_current' => floatval($sensorDataLatestCurrent['current']),
                    'user_input_current_multiplier' => floatval($sensorDataLatestCurrent['current_multiplier']),
                    'record_time' => strval($sensorDataLatestCurrent['record_time']),
                    'condition' => '(Ampere Data: ' . floatval($sensorDataLatestCurrent['amp_data']) . ' > ' . 'Current Alarm Param: ' . $currenAlarmParam[0] . ') Or (' . 'Ampere Data: ' . floatval($sensorDataLatestCurrent['amp_data']) . ' < ' . 'Current Alarm Param: ' . $currenAlarmParam[1] . ')'
                    // 'error_success_message' => ''
                    //'recommendation' => strval($recommendation),
                ); 

                //2.49 A > 2.9 A
                //if ( ( floatval($sensorDataLatestCurrent['amp_data']) > $currenAlarmParam[0] ) || 
                //( floatval($sensorDataLatestCurrent['amp_data']) < $currenAlarmParam[1] )  )

                if ( ( floatval($sensorDataLatestCurrent['amp_data']) > $currenAlarmParam[0] )  )
                {
                    //echo $recommendation = 'Check for the Electrical Wiring / Repeated Power Surge may cause damage to the system';
                
                    //$arrCurrent['data'] = array(
                    /*
                    $arrCurrent = array(
                        'sensor_name' => strval('Current'),
                        'record_id' => intval($sensorDataLatestCurrent['record_id']),
                        'device_id' => intval($sensorDataLatestCurrent['device_id']),
                        'amp_data' => intval($sensorDataLatestCurrent['amp_data']),
                        'user_input_current' => intval($sensorDataLatestCurrent['current']),
                        'record_time' => strval($sensorDataLatestCurrent['record_time']),
                        'recommendation' => strval($recommendation),
                    ); 
                    */

                    $arrCurrent['error_success_message'] = 'Abnormal Reading';
                    $errorMessage[] = $arrCurrent;

                } else {
                    $arrCurrent['error_success_message'] = 'Normal Reading';
                    $successMessage[] = $arrCurrent;
                }

                $sensorNotificationCurrentUpdateSql = 'UPDATE `sensor_notifications`';
                $sensorNotificationCurrentUpdateSql .= ' SET `sensor_value` = ' . strval($sensorDataLatestCurrent['record_id']);
                //$sensorNotificationCurrentUpdateSql .= ' WHERE `sensor_notifications`.`sensor_id` = ' . intval(4); //Current
                $sensorNotificationCurrentUpdateSql .= ' WHERE `sensor_notifications`.`sensor_device_id` = ' . $deviceId;
                $sensorNotificationCurrentUpdateSql .= ' AND `sensor_notifications`.`sensor_name` = "' . $sensorNotificationRow['sensor_name'] .'"';
                $connection->getMysqliDB()->query($sensorNotificationCurrentUpdateSql);
            }

            //Sensor Name: Refrigerant
            if(trim(strtoupper($sensorNotificationRow['sensor_name'])) == trim($sensorDataLatestRefrigerant['sensor_name']))
            {
                //If sensor value equal to record_id - continue;
                
                if (intval($sensorNotificationRow['sensor_value']) == $sensorDataLatestRefrigerant['record_id'])
                {
                    // echo 'continue';
                    //continue;
                    $sensorValueCounter++;
                }
                

                //If sensor value not equal to record_id - perform check 
                //And then update sensor_value from sensor notification table
                echo 'Sensor Value(Temperature Record Id): ' . $sensorNotificationRow['sensor_value'];
                echo '<br />';
                echo 'Latest Temperature Record Id: ' . $sensorDataLatestRefrigerant['record_id'];

                $arrRefrigerant = array();
                $recommendation = '';

                $arrRefrigerant = array(
                    'sensor_name' => strval('Refrigerant'),
                    'record_id' => intval($sensorDataLatestRefrigerant['record_id']),
                    'device_id' => intval($sensorDataLatestRefrigerant['device_id']),
                    'vout_data' => floatval($sensorDataLatestRefrigerant['vout_data']),
                    'vref_data' => floatval($sensorDataLatestRefrigerant['vref_data']),
                    'vout_status' => strval($sensorDataLatestRefrigerant['vout_status']),
                    'vref_status' => strval($sensorDataLatestRefrigerant['vref_status']),
                    'alarm_status' => strval($sensorDataLatestRefrigerant['alarm_status']),
                    'record_time' => strval($sensorDataLatestRefrigerant['record_time']),
                    'condition' => '(Vout Data: ' . strval(floatval($sensorDataLatestRefrigerant['vout_data']) . ' >= ' . 'Vref Data: ' . floatval($sensorDataLatestRefrigerant['vref_data'])) . ')'
                    //'error_success_message' => ''
                    //'recommendation' => strval($recommendation),
                );
        
                if (floatval($sensorDataLatestRefrigerant['vout_data']) >= floatval($sensorDataLatestRefrigerant['vref_data']))
                {
                    //echo $recommendation = "Check for\nRefrigerant Leakage";
    
                    //$arrRefrigerant['data'] = array(
                    /*
                    $arrRefrigerant = array(
                        'sensor_name' => strval('Refrigerant'),
                        'record_id' => intval($sensorDataLatestRefrigerant['record_id']),
                        'device_id' => intval($sensorDataLatestRefrigerant['device_id']),
                        'vout_data' => floatval($sensorDataLatestRefrigerant['vout_data']),
                        'vref_data' => floatval($sensorDataLatestRefrigerant['vref_data']),
                        'vout_status' => strval($sensorDataLatestRefrigerant['vout_status']),
                        'vref_status' => strval($sensorDataLatestRefrigerant['vref_status']),
                        'alarm_status' => strval($sensorDataLatestRefrigerant['alarm_status']),
                        'record_time' => strval($sensorDataLatestRefrigerant['record_time']),
                        'condition' => 'vout_data: ' . strval(floatval($sensorDataLatestRefrigerant['vout_data']) . ' >= ' . 'vref_data: ' . floatval($sensorDataLatestRefrigerant['vref_data'])),
                        'recommendation' => strval($recommendation),
                    );
                    */

                    $arrRefrigerant['error_success_message'] = 'Abnormal Reading';
                    $errorMessage[] = $arrRefrigerant;
    
                } else {

                    $arrRefrigerant['error_success_message'] = 'Normal Reading';
                    $successMessage[] = $arrRefrigerant;

                }

                $sensorNotificationRefrigerantUpdateSql = 'UPDATE `sensor_notifications`';
                $sensorNotificationRefrigerantUpdateSql .= ' SET `sensor_value` = ' . strval($sensorDataLatestRefrigerant['record_id']);
                //$sensorNotificationRefrigerantUpdateSql .= ' WHERE `sensor_notifications`.`sensor_id` = ' . intval(1); //Refrigerant
                $sensorNotificationRefrigerantUpdateSql .= ' WHERE `sensor_notifications`.`sensor_device_id` = ' . $deviceId;
                $sensorNotificationRefrigerantUpdateSql .= ' AND `sensor_notifications`.`sensor_name` = "' . $sensorNotificationRow['sensor_name'] .'"';
                
                echo $sensorNotificationRefrigerantUpdateSql;
                //UPDATE `sensor_notifications` SET `sensor_value` = 48084 
                //WHERE `sensor_notifications`.`sensor_device_id` = 1 AND `sensor_notifications`.`sensor_name` = Refrigerant
                $connection->getMysqliDB()->query($sensorNotificationRefrigerantUpdateSql);
            }

            //Sensor Name: Vibration
            if(trim(strtoupper($sensorNotificationRow['sensor_name'])) == trim($sensorDataLatestVibration['sensor_name']))
            {
                //If sensor value equal to record_id - continue;
                
                if (intval($sensorNotificationRow['sensor_value']) == $sensorDataLatestVibration['record_id'])
                {
                    // echo 'continue';
                    //continue;
                    $sensorValueCounter++;
                }
                

                //If sensor value not equal to record_id - perform check 
                //And then update sensor_value from sensor notification table
                echo 'Sensor Value(Temperature Record Id): ' . $sensorNotificationRow['sensor_value'];
                echo '<br />';
                echo 'Latest Temperature Record Id: ' . $sensorDataLatestVibration['record_id'];

                $arrVibration = array();
                $recommendation = '';

                $arrVibration = array(
                    'sensor_name' => strval('Vibration'),
                    'record_id' => intval($sensorDataLatestVibration['record_id']),
                    'device_id' => intval($sensorDataLatestVibration['device_id']),
                    'r_data' => floatval($sensorDataLatestVibration['r_data']),
                    'user_input_vibration' => floatval($sensorDataLatestVibration['vibration']),
                    'record_time' => strval($sensorDataLatestVibration['record_time']),
                    'condition' => '(R Data: ' . floatval($sensorDataLatestVibration['r_data']) . ' > ' . 'Vibration Alarm Param: ' .  floatval($sensorDataLatestVibration['vibration']) . ')'
                    //'error_success_message' => ''
                    //'recommendation' => strval($recommendation),
                );
        
                //if (floatval($sensorDataLatestVibration['r_data'] > 22.0))
                if (floatval($sensorDataLatestVibration['r_data']) > floatval($sensorDataLatestVibration['vibration']))
                {
                    //echo $recommendation = 'Check for faulty compressor or any loose screws';
    
                    //$arrVibration['data'] = array(
                    /*
                    $arrVibration = array(
                        'sensor_name' => strval('Vibration'),
                        'record_id' => intval($sensorDataLatestVibration['record_id']),
                        'device_id' => intval($sensorDataLatestVibration['device_id']),
                        'r_data' => intval($sensorDataLatestVibration['r_data']),
                        'user_input_vibration' => floatval($sensorDataLatestVibration['vibration']),
                        'record_time' => strval($sensorDataLatestVibration['record_time']),
                        'recommendation' => strval($recommendation),
                    );
                    */
                    
                    $arrVibration['error_success_message'] = 'Abnormal Reading';
                    $errorMessage[] = $arrVibration;
                } else {
                    $arrVibration['error_success_message'] = 'Normal Reading';
                    $successMessage[] = $arrVibration;
                }

                $sensorNotificationVibrationUpdateSql = 'UPDATE `sensor_notifications`';
                $sensorNotificationVibrationUpdateSql .= ' SET `sensor_value` = ' . strval($sensorDataLatestVibration['record_id']);
                //$sensorNotificationVibrationUpdateSql .= ' WHERE `sensor_notifications`.`sensor_id` = ' . intval(5); //Vibration
                $sensorNotificationVibrationUpdateSql .= ' WHERE `sensor_notifications`.`sensor_device_id` = ' . $deviceId;
                $sensorNotificationVibrationUpdateSql .= ' AND `sensor_notifications`.`sensor_name` = "' . $sensorNotificationRow['sensor_name'] .'"';
                $connection->getMysqliDB()->query($sensorNotificationVibrationUpdateSql);
            }


            //unset($sensorNotification); //Destroy or Unset Object

        }

        //Send Email script here...

        $errorSuccessMessage = array_unique(array_merge($errorMessage, $successMessage), SORT_REGULAR);

        //If ErrorSuccessMessage array is empty 
        //Continue with the iteration of the next device.
        if(empty($errorSuccessMessage)) {
            continue;
        }

        /*
        echo '<pre>';
        print_r($errorSuccessMessage);
        echo '</pre>';

        echo 'errorSuccessMessage: ' . count($errorSuccessMessage);
        echo '<br />';
        echo 'sensorValueCounter: ' . $sensorValueCounter;
        echo '<br />';
        */
        
        // If the SensorValueCounter variable and ErrorSucessMessage array are equal in count or size don't send email. 
        //Continue with the iteration of the next device.
        if ($sensorValueCounter == count($errorSuccessMessage)) {
            continue;
        }


        echo '<pre>';
        var_dump($errorSuccessMessage);
        echo '</pre>';

        $errorSuccessMessageKey = array_search('Abnormal Reading', array_column($errorSuccessMessage, "error_success_message"));

        echo '<pre>';
        var_dump($errorSuccessMessageKey);
        echo '</pre>';

        /*
        if (in_array('Abnormal Reading', $errorSuccessMessage)) {
            echo 'Has Abnormal Reading Included In The Array.';
        } else {
            echo 'Has No Abnormal Reading Included In The Array.';
        }
        */

        if ($errorSuccessMessageKey !== false) 
        {

            //if(sizeof($errorMessage) != 0) 
            if(sizeof($errorSuccessMessage) != 0) 
            {
                /*$x = 0;
                $countSuccessArr = array();
                for($x = 0; $x < sizeof($errorSuccessMessage); $x++)
                {
                    //sizeof($errorSuccessMessage);
                    If ('Success' == $errorSuccessMessage[$x]['error_success_message'])
                    {
                        $countSuccessArr[] = $errorSuccessMessage[$x]['error_success_message'];
                    }
                }
                echo count($countSuccessArr);

                if(!empty($countSuccessArr)){
                    continue;
                }*/

                $i = 0;
                $emailHTMLBody = '';

                $emailHTMLBody .= '<div style="font-size:12px; font-family: Arial, Helvetica, sans-serif;">';
                //foreach($errorMessage as $error)


                foreach($errorSuccessMessage as $error)
                {  

                    //echo '<pre>';
                    //print_r($error);
                    //echo '</pre>';

                    if($i == 0) {
                        $emailHTMLBody = '<h3 style="font-family: Arial, Helvetica, sans-serif;">Device ID: ' . $error['device_id'] . '</h3>';
                    }
                    //$emailHTMLBody = '<strong>Device ID:</strong> ' . $error['device_id'] . '<br>';
                    $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Sensor Name:</strong> ' . $error['sensor_name'] . '<br>';
                    $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Record ID:</strong> ' . $error['record_id'] . '<br>';
                    $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Record Time:</strong> ' . $error['record_time'] . '<br>';

                    if('Abnormal Reading' == $error['error_success_message']) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Message:</strong> ' . '<span style = "color:#DC3545; font-weight:bold; font-family: Arial, Helvetica, sans-serif;" ><i>' . $error['error_success_message'] . '</i></span>' . '<br>';
                    } else {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Message:</strong> ' . '<span style = "color:#198754; font-weight:bold; font-family: Arial, Helvetica, sans-serif;" ><i>' . $error['error_success_message'] . '</i></span>' . '<br>';
                    }
                    //$emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Recommendation:</strong> ' . $error['recommendation'] . '<br><br>';
                    //$emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Sensor Data Condition:</strong> ' . $error['condition'] . '<br><br>';
                    

                    //Refrigerant
                    if(array_key_exists('vout_data', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Voltage Out = </strong> ' . $error['vout_data'] . ' V' . '<br>';
                    }
                    
                    if(array_key_exists('vref_data', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Voltage Reference = </strong> ' . $error['vref_data'] . ' V' . '<br>';
                    }

                    //Temperature
                    if(array_key_exists('temp_data', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Temperature = </strong> ' . $error['temp_data'] . ' &#8451;' . '<br>';
                    }

                    if(array_key_exists('user_input_temperature', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Set Temperature Value = </strong> ' . $error['user_input_temperature'] . ' &#8451;' . '<br>';
                    }

                    /*
                    if(array_key_exists('temp_threshold_value', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Set Temperature Value = </strong> ' . $error['temp_threshold_value'] . ' &#8451;' . '<br>';
                    }
                    */

                    //Humidity
                    if(array_key_exists('hmd_data', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Humidity Reading = </strong> ' . $error['hmd_data'] . ' &#37;' . '<br>';
                    }

                    if(array_key_exists('humid_max', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Standard Humidity Max Value = </strong> ' . $error['humid_max'] . ' &#37;' . '<br>';
                    }

                    if(array_key_exists('humid_min', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Standard Humidity Min Value = </strong> ' . $error['humid_min'] . ' &#37;' . '<br>';
                    }


                    //Current
                    if(array_key_exists('amp_data', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Ampere Data = </strong> ' . $error['amp_data'] . ' A' . '<br>';
                    }

                    if(array_key_exists('user_input_current', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Set Current Value = </strong> ' . (floatval($error['user_input_current']) * floatval($error['user_input_current_multiplier'])) . ' A' . '<br>';
                    }


                    //Vibration
                    if(array_key_exists('r_data', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">R Data = </strong> ' . $error['r_data'] . ' m&#47;s&#94;<sup>2</sup>' . '<br>';
                    }

                    if(array_key_exists('user_input_vibration', $error)) {
                        $emailHTMLBody .= '<strong style="font-family: Arial, Helvetica, sans-serif;">Standard Vibration Value = </strong> ' . $error['user_input_vibration'] . ' m&#47;s&#94;<sup>2</sup>' . '<br>';
                    }

                    $emailHTMLBody .= '<hr><br>';
                    $i++;
                }

                $emailHTMLBody .= '</div>';

                // echo $emailHTMLBody;
            
                
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'ateneo.denaga.iot.project@gmail.com';  //SMTP username
                    $mail->Password   = 'pguwevjjiadwqnzp';                     //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


                    //Recipients
                    $mail->setFrom('ateneo.denaga.iot.project@gmail.com', 'IOT Alarm Monitoring System');

                    //Get the Recipient/s
                    $sqlUserInfo = 'SELECT DISTINCT usr_creds.*, usr_dt.* FROM `user_credentials` AS usr_creds';
                    $sqlUserInfo .= ' INNER JOIN `user_data` AS usr_dt ON usr_creds.user_id = usr_dt.user_id';
                    $sqlUserInfo .= ' ORDER BY `usr_creds`.create_date DESC';

                    $userInfoResult =  $connection->getMysqliDB()->query($sqlUserInfo);

                    $mailAddress = '';
                    $mailAddressName = '';

                    if ($userInfoResult->num_rows == 1) 
                    {   
                        $userInfoRow = $userInfoResult->fetch_assoc();

                        $mailAddress = $userInfoRow['email'];
                        $mailAddressName = ucwords($userInfoRow['first_name']) . ' ' . ucwords($userInfoRow['last_name']);

                        $mail->addAddress($mailAddress, $mailAddressName);       //Add a recipient

                    } 
                    elseif ($userInfoResult->num_rows != 0 && $userInfoResult->num_rows > 1) 
                    {
                        while($userInfoRow = $userInfoResult->fetch_assoc()) 
                        {
                            $mailAddress = $userInfoRow['email'];
                            $mailAddressName = ucwords($userInfoRow['first_name']) . ' ' . ucwords($userInfoRow['last_name']);

                            $mail->addAddress($mailAddress, $mailAddressName);     //Add a recipient
                        }
                    }
                    //$mail->addReplyTo('info@example.com', 'Information');
                    //$mail->addCC('cc@example.com');
                    //$mail->addBCC('bcc@example.com');

                    //Attachments
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML

                
                
                    /*
                    $mail->Subject = 'Here is the subject';
                    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                    */

                    
                
                    $SubjectDate = date('l, d F Y h:i:s A');
                    $mail->Subject = '[IOT Alarm Monitoring System] Sensor Alert Notification - (Device ID: ' . $deviceId . ') - ' . $SubjectDate;
                    $mail->Body    = $emailHTMLBody;

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

            
                
            }
        
        }
        
    }
}