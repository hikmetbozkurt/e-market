<?php

$dsn = 'mysql:host=localhost;dbname=test;charset=utf8mb4' ; 
$user = 'root' ; 
$pass = '' ; 

session_start();


try {
    $db = new PDO($dsn, $user, $pass) ; 
} catch (Exception $ex) {
     echo '<p>DB Connect Error : ' . $ex->getMessage() . '</p>' ; 
    exit; }