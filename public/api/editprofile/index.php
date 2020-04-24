<?php

session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $file = $_FILES['file'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $description = $_POST['des$description'];


    var_dump($file);
    var_dump($username);
    var_dump($name);
    var_dump($description);


} else {
    exit;
}