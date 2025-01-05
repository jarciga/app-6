<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\AlarmParamsModel;

class AlarmParamsController
{
    protected $alarmParamsModel = null;

    public function __construct() 
    {
        $this->alarmParamsModel = new AlarmParamsModel();
    }

    public function alarmParamsDataInput($name, $deviceId, $userId, $temperature, $current)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') 
        {

           $data = json_encode($this->alarmParamsModel->alarmParamsQuery($name, $deviceId, $userId, $temperature, $current));
           
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Methods: GET, PUT, PATCH, POST, DELETE");
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Content-Type: application/json; charset=utf-8');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function alarmParamsReadDataOutput($deviceId = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->alarmParamsModel->alarmParamsReadQuery($deviceId));

            header_remove('Set-Cookie');
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function alarmParamsUpdateDataInput($name, $deviceId, $userId, $temperature, $current)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') 
        {
           $data = json_encode($this->alarmParamsModel->alarmParamsQuery($name, $deviceId, $userId, $temperature, $current));

            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Methods: GET, PUT, PATCH, POST, DELETE");
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Content-Type: application/json; charset=utf-8');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}