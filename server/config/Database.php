<?php

namespace AdnuAcrms\Config;

Class Database {
    // Localhost connection
    public $hostname = 'localhost';
    public $username = 'root';
    public $password = '';
    public $database = '';
    public $connectionString = '';

    public function __construct() 
    {
        // echo 'Database Class';
        
        $this->connectionString = array(
            'hostname' => $this->hostname,
            'username' => $this->username,
            'password' => $this->password,
            'database' => $this->database,
        );
    } 
}
