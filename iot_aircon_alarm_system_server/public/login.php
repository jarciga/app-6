<?php

$appDirectory = __DIR__ . '/..';
$appPath = realpath(rtrim($appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR;

//require $appPath . 'vendor\autoload.php';
require $appPath . 'vendor/autoload.php';

//POST
$_SERVER['REQUEST_METHOD'] = 'POST';
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    $username = (isset($_POST['username']) || !empty($_POST['username'])) ? strval($_POST['username']) : '';
    $password = (isset($_POST['password']) || !empty($_POST['password'])) ? strval($_POST['password']) : '';

    $var = new AdnuAcrms\Controllers\LoginController();
    echo $var->loginDataInput($username, $password);
}
else
{
    header_remove('Set-Cookie');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => array('error' => 'HTTP/1.1 400 Bad Request')));    
    exit();
}