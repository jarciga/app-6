<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\TemperatureModel;

class TemperatureController 
{
    protected $temperatureModel = null;

    public function __construct() 
    {
        $this->temperatureModel = new TemperatureModel();
    }
    public function temperatureDataOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->temperatureModel->temperatureQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function temperatureDataAlarmHistoryOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->temperatureModel->temperatureAlarmHistoryQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function temperatureAndHumidityDataAlarmHistoryOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->temperatureModel->temperatureAndHumidityAlarmHistoryQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}

