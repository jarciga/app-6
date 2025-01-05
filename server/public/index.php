<?php

$appDirectory = __DIR__ . '/..';
$appPath = realpath(rtrim($appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR;

//require $appPath . 'vendor\autoload.php';
require $appPath . 'vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

//GET
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {

    if (isset($_GET['req']) || !empty($_GET['req']))
    {
        $limit = (isset($_GET['limit']) || !empty($_GET['limit'])) ? intval($_GET['limit']) : null;
        $deviceId = (isset($_GET['deviceId']) || !empty($_GET['deviceId'])) ? intval($_GET['deviceId']) : null;

        if ($_GET['req'] == 'device') // Use in the Main Menu Sensor or Device DropdownButton
        {
            $var = new AdnuAcrms\Controllers\DeviceController();
            echo $var->deviceOutput($limit);
        }  
        elseif ($_GET['req'] == 'sensor')
        {
            $var = new AdnuAcrms\Controllers\SensorController();
            echo $var->sensorOutput($deviceId);
        }   
        elseif ($_GET['req'] == 'temperature')
        {
            $var = new AdnuAcrms\Controllers\TemperatureController();
            echo $var->temperatureDataOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'humidity')
        {
            $var = new AdnuAcrms\Controllers\HumidityController();
            echo $var->humidityDataOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'current')
        {
            $var = new AdnuAcrms\Controllers\CurrentController();
            echo $var->currentDataOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'refrigerant')
        {
            $var = new AdnuAcrms\Controllers\RefrigerantController();
            echo $var->refrigerantDataOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'vibration')
        {
            $var = new AdnuAcrms\Controllers\VibrationController();
            echo $var->vibrationDataOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'alarm-history')
        {
            $var = new AdnuAcrms\Controllers\TestController();
            echo $var->alarmHistoryDataOutput($limit);
        }
        elseif ($_GET['req'] == 'temperatureAlarmHistory')
        {
            $var = new AdnuAcrms\Controllers\TemperatureController();
            echo $var->temperatureDataAlarmHistoryOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'humidityAlarmHistory')
        {
            $var = new AdnuAcrms\Controllers\HumidityController();
            echo $var->humidityDataAlarmHistoryOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'humidityTemperatureAlarmHistory') //Don't Use this
        {
            $var = new AdnuAcrms\Controllers\HumidityController();
            echo $var->humidityAndTemperatureDataAlarmHistoryOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'currentAlarmHistory')
        {
            $var = new AdnuAcrms\Controllers\CurrentController();
            echo $var->currentDataAlarmHistoryOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'refrigerantAlarmHistory')
        {
            $var = new AdnuAcrms\Controllers\RefrigerantController();
            echo $var->refrigerantDataAlarmHistoryOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'vibrationAlarmHistory')
        {
            $var = new AdnuAcrms\Controllers\VibrationController();
            echo $var->vibrationDataAlarmHistoryOutput($deviceId, $limit);
        }
        elseif ($_GET['req'] == 'alarmParams')
        {
            $var = new AdnuAcrms\Controllers\AlarmParamsController();
            echo $var->alarmParamsReadDataOutput($deviceId); // Get only one data
        }
        elseif ($_GET['req'] == 'userGroup')
        {
            $var = new AdnuAcrms\Controllers\UserGroupController();
            echo $var->userGroupOutput($limit); // Get only one data
        }
        /*elseif ($_GET['req'] == 'tmdhmd') //Testing
        {
            $var = new AdnuAcrms\Controllers\TestController();
            echo $var->tempHmdDataOutput($limit);
        }*/
        else
        {
            header_remove('Set-Cookie');
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array('error' => array('error' => 'HTTP/1.1 400 Bad Request')));    
            exit();
        }
    }
}
else
{
    header_remove('Set-Cookie');
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => array('error' => 'HTTP/1.1 400 Bad Request')));    
    exit();
}