<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\VibrationModel;

class VibrationController 
{
    protected $vibrationModel = null;

    public function __construct() 
    {
        $this->vibrationModel = new VibrationModel();
    }
    public function vibrationDataOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->vibrationModel->vibrationQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function vibrationDataAlarmHistoryOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->vibrationModel->vibrationAlarmHistoryQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}

