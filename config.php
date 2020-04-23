<?php

$host = 'remotemysql.com';
$dbname = 'hKbfHbty3s';
$dbuser = 'hKbfHbty3s';
$dbpwd = 'HJJESWjZAc';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpwd);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}
    
