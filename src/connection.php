<?php

session_start();

require_once (dirname(__FILE__) . '/config.php');
require_once (dirname(__FILE__) . '/User.php');

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbBaseName);

if($conn->connect_errno){
    die('db connection not initialized properly' . $conn->connect_errno);
}

User::SetConnection($conn);












/*
$user1 = User::getUserById(1);
var_dump($user1);

$user1->setDescription("Nowy opis");
$isWorking = $user1->saveToDb();

var_dump($user1);
var_dump($isWorking);



$user1 = User::logInUser("test@pl", "12345");
var_dump($user1);

$user2 = User::getUserById(1);
var_dump($user2);

$user3 = User::GetAllUsers();
var_dump($user3);


$user1 = User::RegisterUser("Marek", "test5@pl", "12345", "12345", "Opis Jacka");
var_dump($user1);
*/
