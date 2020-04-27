<?php

session_start();
require "../../../vendor/autoload.php";
use Dao\UsuarioMySql;

require "../../../config.php";

$hora = date('Y-m-d H:i:s');

function specialCharacters($string) {
    $length = strlen($string);
    $string = strtolower($string);
    for($i = 0; $i < $length; $i++) {
        if(($string[$i] >= 'a' && $string[$i] <= 'z') ||($string[$i] == '_' ||$string[$i] == '.'))
            continue;
        else 
            return true;
    }

    return false;
}

if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $usuarioDb = new UsuarioMySql($pdo);
    $file = $_FILES['file'];
    $username = $_POST['username'];
    $nome = $_POST['name'];
    $description = $_POST['description'];
    if(specialCharacters($username) == false) {
        if($file['tmp_name'] != "" && ($file['type'] == 'image/jpeg' || $file['type'] == 'image/png')) {
            $img_url = $usuarioDb->getImgUrlById($id);
            if($img_url == 'profiles/usuario/profile.jpg') {
                $apagou = true;
            } else {
                $apagou = unlink($_SERVER['DOCUMENT_ROOT'].'/'.$img_url, null);
            }
            if($apagou == true) {
                $temp = explode(".", $_FILES["file"]["name"]);
                $newfilename = md5($id.$hora) . '.' . end($temp);
                move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/'. "profiles/" . $newfilename);
                $img_url = "profiles/" . $newfilename;

                $usuarioDb->editUser($id, $img_url, $username, $nome, $description);
                echo json_encode($usuarioDb->getUserToEditById($id), true); 
            } else {
                echo json_encode($usuarioDb->getUserToEditById($id), true); 
            }
        } else {
            $usuarioDb->editUser($id, "", $username, $nome, $description);
            echo json_encode($usuarioDb->getUserToEditById($id), true);
        }
    } else {
        echo json_encode($usuarioDb->getUserToEditById($id), true); 
    }
} else {
    exit;
}