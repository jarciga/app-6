<?php

namespace AdnuAcrms\Config;

$appDirectory = __DIR__ . '/..';
$appPath = realpath(rtrim($appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR;
//require $appPath . 'vendor\autoload.php';
require $appPath . 'vendor/autoload.php';

use AdnuAcrms\Config\Database;

Class Connection {

    public $mysqliDB = null;

    public function __construct()
    {
        //echo 'Connection Class';

        $database = new Database();

        $this->mysqliDB = new \mysqli(
            $database->connectionString['hostname'], 
            $database->connectionString['username'], 
            $database->connectionString['password'], 
            $database->connectionString['database']
        );
        
        if ($this->mysqliDB->connect_errno) {
            throw new RuntimeException('Database connection error: ' . $this->mysqliDB->connect_error);
            exit();
        }
    }

    public function getMysqliDB()
    {
        return $this->mysqliDB;
    }
}


