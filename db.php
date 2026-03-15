<?php

    $host = "localhost";
    $db_name = "rest_api_db";
    $username = "root";
    $password = "";

try{ 
    $pdo = new PDO("mysql:host=$host; dbname=$db_name, $username, $passowrd");

    $pdo->setAttribute(PDO::ATTR_ERRORMODE, PDO::ERRORMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>