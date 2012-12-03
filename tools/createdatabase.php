<?php
$host="127.0.0.1"; 
$root="root"; 
$root_password="root"; 
$db="mp"; 

try {
    $dbh = new PDO("mysql:host=$host", $root, $root_password);

    $dbh->exec("CREATE DATABASE `$db`;") 
    or die(print_r($db->errorInfo(), true));

} catch (PDOException $e) {
    die("DB ERROR: ". $e->getMessage());
}