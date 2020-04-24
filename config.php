<?php

$host = 'localhost';
$dbname = 'flowgram';
$dbuser = 'paulowski';
$dbpwd = 'xr2$gpdL#jC)uQ?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpwd);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}
    
