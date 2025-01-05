<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\SensorNotificationModel;

class SensorNotificationController 
{
    protected $sensorNotificationModel = null;

    public function __construct() 
    {
        $this->sensorNotificationModel = new SensorNotificationModel();
    }
    public function sensorNotifcationOutput($deviceId)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = $this->sensorNotificationModel->SensorNotifcationQuery($deviceId);

            header_remove('Set-Cookie');
            
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            
            return $data; 
            exit();
        }
    }

    public function sensorDataLatestTemperatureOutput($deviceId) 
    {
        return $this->sensorNotificationModel->SensorDataLatestTemperature($deviceId);
    }
    
    public function sensorDataLatestHumidityOutput($deviceId) 
    {
        return $this->sensorNotificationModel->SensorDataLatestHumidity($deviceId); 
    }

    public function sensorDataLatestCurrentOutput($deviceId) 
    {
        return $this->sensorNotificationModel->SensorDataLatestCurrent($deviceId);
    }

    public function sensorDataLatestRefrigerantOutput($deviceId) 
    {
        return $this->sensorNotificationModel->SensorDataLatestRefrigerant($deviceId);
    }

    public function sensorDataLatestVibrationOutput($deviceId) 
    {
        return $this->sensorNotificationModel->SensorDataLatestVibration($deviceId);
    }





}

