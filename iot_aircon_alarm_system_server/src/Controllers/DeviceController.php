<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\DeviceModel;

class DeviceController 
{
    protected $deviceModel = null;

    public function __construct() 
    {
        $this->deviceModel = new DeviceModel();
    }
    public function deviceOutput($limit)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->deviceModel->deviceQuery($limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}

