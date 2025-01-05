<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\UserGroupModel;

class UserGroupController 
{
    protected $userGroupModel = null;

    public function __construct() 
    {
        $this->userGroupModel = new UserGroupModel();
    }
    public function userGroupOutput($limit)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->userGroupModel->userGroupQuery($limit));

            header_remove('Set-Cookie');
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}

