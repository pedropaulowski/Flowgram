<?php

$host = 'http://13.82.208.202/';
$dbname = 'flowgram';
$dbuser = 'root';
$dbpwd = 'UZUMymw123j6t2hybt26';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpwd);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}
    
