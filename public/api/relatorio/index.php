<?php

use Dao\MensagemMySql;
use Dao\UsuarioMySql;

session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    require '../../../config.php';
    require '../../../vendor/autoload.php';
    $usuarioDb = new UsuarioMySql($pdo);
    $mensagemDb = new MensagemMySql($pdo);

    $id = $_SESSION['id'];

    $usuario = $usuarioDb->getUserById($id);

    
    echo "Nome: ".htmlspecialchars($usuario['nome'])."<br/>";
    echo "Username: ".htmlspecialchars($usuario['username'])."<br/>";
    echo "Ultimo acesso: ".htmlspecialchars($usuario['ultimo_acesso'])."<br/>";
    echo "Recado: ".htmlspecialchars($usuario['descricao'])."<br/>";
    echo "Chave pública: ".bin2hex($usuario['chave_publica'])."<br/>";
    echo "Foto de perfil: <a href='http://192.168.1.5/".$usuario['img_url']."' download>"."Download</a><br/>";
    echo "Entrou: ".htmlspecialchars($usuario['created_at'])."<br/>";


} else {
    exit;
}