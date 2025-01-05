<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\RefrigerantModel;

class RefrigerantController 
{
    protected $refrigerantModel = null;

    public function __construct() 
    {
        $this->refrigerantModel = new RefrigerantModel();
    }
    public function refrigerantDataOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->refrigerantModel->refrigerantQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function refrigerantDataAlarmHistoryOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->refrigerantModel->refrigerantAlarmHistoryQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}

