<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\HumidityModel;

class HumidityController 
{
    protected $humidityModel = null;

    public function __construct() 
    {
        $this->humidityModel = new HumidityModel();
    }
    public function humidityDataOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->humidityModel->humidityQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function humidityDataAlarmHistoryOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->humidityModel->humidityAlarmHistoryQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function humidityAndTemperatureDataAlarmHistoryOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->humidityModel->humidityAndTemperatureAlarmHistoryQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}

