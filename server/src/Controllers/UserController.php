<?php

namespace AdnuAcrms\Controllers;

use AdnuAcrms\Models\UserModel;

date_default_timezone_set('Asia/Taipei');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use AdnuAcrms\Config\Connection;

$appDirectory = dirname(__DIR__, 2);
$appPath = realpath(rtrim($appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR;
//require $appPath . 'vendor\autoload.php';
require $appPath . 'vendor/autoload.php';
class UserController
{
    protected $userModel = null;

    public function __construct() 
    {
        $this->userModel = new UserModel();
    }

    public function userDataInput($userName, $password, $email, $groupId, $firstName, $middleName, $lastName)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') 
        {
            $data = json_encode($this->userModel->userCreateQuery($userName, $password, $email, $groupId, $firstName, $middleName, $lastName));

            //Check the message if it was an 'Error' or 'Success'

            $dataJsonDecoded = json_decode($data, true);

            if ('Success' == $dataJsonDecoded['message']) 
            {
                $mail = new PHPMailer(true);
                
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'ateneo.denaga.iot.project@gmail.com';  //SMTP username
                $mail->Password   = 'pguwevjjiadwqnzp';                     //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                           //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mailAddress = $dataJsonDecoded['email'];
                $mailAddressName = ucwords($dataJsonDecoded['first_name']) . ' ' . ucwords($dataJsonDecoded['last_name']);

                $mail->setFrom('ateneo.denaga.iot.project@gmail.com', 'IOT Alarm Monitoring System');
                $mail->addAddress($mailAddress, $mailAddressName);     //Add a recipient
                //$mail->addAddress('ladipajimola@gmail.com', 'Gladys Dette Pajimolo');               //Name is optional
                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');

                $mailBody = 'Dear ' . ucwords($dataJsonDecoded['username']) . ',<br><br>';
                $mailBody .= 'Welcome to the IOT Alarm Monitoring System!<br><br>';
                $mailBody .= 'Your registration username and password are listed below and you should keep this email in case you forget them:<br><br>';
                $mailBody .= '<strong>Username: ' . $dataJsonDecoded['username'] . '</strong><br><br>';
                $mailBody .= '<strong>Password: ' . $dataJsonDecoded['password'] . '</strong><br><br>';
                $mailBody .= 'Sincerely,<br><br>';
                $mailBody .= 'IOT Alarm Monitoring System Team';
            
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $SubjectDate = date('l, d F Y h:i:s A');
                $mail->Subject = '[IOT Alarm Monitoring System] Your Registration was Successful!' . $SubjectDate;
                $mail->Body    = $mailBody;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
                $mail->send();
            }

            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Methods: GET, PUT, PATCH, POST, DELETE");
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Content-Type: application/json; charset=utf-8');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function userReadDataOutput($userId = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            $data = json_encode($this->userModel->userReadQuery($userId));

            header_remove('Set-Cookie');
            header("Access-Control-Allow-Origin: *");
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }

    public function userUpdateDataInput($userId, $userName, $password, $email, $groupId, $firstName, $middleName, $lastName)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') 
        {
           $data = json_encode($this->userModel->userQuery($userId, $userName, $password, $email, $groupId, $firstName, $middleName, $lastName));

            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Methods: GET, PUT, PATCH, POST, DELETE");
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Content-Type: application/json; charset=utf-8');
            header('HTTP/1.1 200 OK');

            return $data; 
            exit();
        }
    }
}