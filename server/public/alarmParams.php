<?php

$appDirectory = __DIR__ . '/..';
$appPath = realpath(rtrim($appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR;
//require $appPath . 'vendor\autoload.php';
require $appPath . 'vendor/autoload.php';

//POST
$_SERVER['REQUEST_METHOD'] = 'POST';
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {

    $name = (isset($_POST['name']) || !empty($_POST['name'])) ? intval($_POST['name']) : 'Alarm_'.mt_rand();
    $deviceId = (isset($_POST['deviceId']) || !empty($_POST['deviceId'])) ? intval($_POST['deviceId']) : 3;
    $userId = (isset($_POST['userId']) || !empty($_POST['userId'])) ? intval($_POST['userId']) : 5; //1: is Admin, 5: Android
    $temperature = (isset($_POST['temperature']) || !empty($_POST['temperature'])) ? doubleval($_POST['temperature']) : 0.0;
    $current = (isset($_POST['current']) || !empty($_POST['current'])) ? doubleval($_POST['current']) : 0.0;
    
    $var = new AdnuAcrms\Controllers\AlarmParamsController();
    echo $var->alarmParamsDataInput($name, $deviceId, $userId, $temperature, $current);
}
else
{
    header_remove('Set-Cookie');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, PUT, PATCH, POST, DELETE");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => array('error' => 'HTTP/1.1 400 Bad Request')));    
    exit();
}