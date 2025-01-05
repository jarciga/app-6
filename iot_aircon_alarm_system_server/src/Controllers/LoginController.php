<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\LoginModel;

class LoginController
{
    protected $loginModel = null;

    public function __construct() 
    {
        $this->loginModel = new LoginModel();
    }
    public function loginDataInput($username, $password)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') 
        {

           $data = json_encode($this->loginModel->loginQuery($username, $password));

            header("Access-Control-Allow-Origin: *");

            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Content-Type: application/json; charset=utf-8');
            return $data; 
            exit();
        }
    }
}