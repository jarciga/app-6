<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\CurrentModel;

class CurrentController 
{
    protected $currentModel = null;

    public function __construct() 
    {
        $this->currentModel = new CurrentModel();
    }
    public function currentDataOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->currentModel->currentQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function currentDataAlarmHistoryOutput($deviceId = null, $limit = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->currentModel->currentAlarmHistoryQuery($deviceId, $limit));

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}

