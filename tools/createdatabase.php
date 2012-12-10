<?php
$host="127.0.0.1"; 
$root="root"; 
$root_password="root"; 
$db="api"; 

try {
    $dbh = new PDO("mysql:host=$host;dbname=$db", $root, $root_password);
    $query = $dbh->query("") ;
    
    var_dump($query);

} catch (PDOException $e) {
    die("DB ERROR: ". $e->getMessage());
}