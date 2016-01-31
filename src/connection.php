<?php

session_start();

require_once (dirname(__FILE__) . '/config.php');
require_once (dirname(__FILE__) . '/User.php');

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbBaseName);

if($conn->connect_errno){
    die('db connection not initialized properly' . $conn->connect_errno);
}

User::SetConnection($conn);

