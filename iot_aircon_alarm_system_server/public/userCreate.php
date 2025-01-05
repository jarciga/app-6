<?php
$appDirectory = __DIR__ . '/..';
$appPath = realpath(rtrim($appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR;

//require $appPath . 'vendor\autoload.php';
require $appPath . 'vendor/autoload.php';

//POST
$_SERVER['REQUEST_METHOD'] = 'POST';
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {

    $userName = (isset($_POST['username']) || !empty($_POST['username'])) ? strval($_POST['username']) : null;
    $password = (isset($_POST['password']) || !empty($_POST['password'])) ? strval($_POST['password']) : null;
    $email = (isset($_POST['email']) || !empty($_POST['email'])) ? strval($_POST['email']) : null;
    $groupId = (isset($_POST['groupId']) || !empty($_POST['groupId'])) ? strval($_POST['groupId']) : null;
    $firstName = (isset($_POST['firstName']) || !empty($_POST['firstName'])) ? strval($_POST['firstName']) : null;
    $middleName = (isset($_POST['middleName']) || !empty($_POST['middleName'])) ? strval($_POST['middleName']) : null;
    $lastName = (isset($_POST['lastName']) || !empty($_POST['lastName'])) ? strval($_POST['lastName']) : null;

    $var = new AdnuAcrms\Controllers\UserController();
    echo $var->userDataInput($userName, $password, $email, $groupId, $firstName, $middleName, $lastName);
}
else
{
    header_remove('Set-Cookie');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, PUT, PATCH, POST, DELETE");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => array('error' => 'HTTP/1.1 400 Bad Request')));    
    exit();
}