<?php
use Dao\MensagemMySql;
use Dao\UsuarioMySql;

session_start();

if(isset($_SESSION['id']) && !empty($_SESSION)) {
    require '../../../config.php';
    require '../../../vendor/autoload.php';
    $usuarioDb = new UsuarioMySql($pdo);
    $mensagemDb = new MensagemMySql($pdo);
    $id = $_SESSION['id'];

    $conversas = $mensagemDb->getUserChats($id);

    $array = [];

    foreach($conversas as $conversa) {
        $remetente = $conversa['usuario1'];
        $destinatario = $conversa['usuario2'];

        $user = ($id == $destinatario) ? $remetente : $destinatario;
        $total = $mensagemDb->countTodasPorConversa($id, $user);

        $user = $usuarioDb->getUserToEditById($user);


        $array[] = [
            "usuario" => $user['nome'],
            "img_url" => $user['img_url'],
            "total" => $total
        ];

    }

    echo json_encode($array, true);
    exit;
}
exit;