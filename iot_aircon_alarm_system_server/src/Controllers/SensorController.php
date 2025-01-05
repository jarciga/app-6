<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\SensorModel;

class SensorController 
{
    protected $sensorModel = null;

    public function __construct() 
    {
        $this->sensorModel = new SensorModel();
    }
    public function sensorOutput($deviceId)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->sensorModel->sensorQuery($deviceId));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            
            return $data; 
            exit();
        }
    }
}

