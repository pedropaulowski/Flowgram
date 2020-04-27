<?php

use Dao\MensagemMySql;
use Dao\UsuarioMySql;

session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id']) && isset($_GET['id']) && isset($_GET['chave_privada'])) {
    require '../../../config.php';
    require '../../../vendor/autoload.php';
    $usuarioDb = new UsuarioMySql($pdo);
    $mensagemDb = new MensagemMySql($pdo);

    $id = $_SESSION['id'];
    $id_destinatario = $_GET['id'];
    
    $usuario1 = $usuarioDb->getUserToEditById($id);
    $usuario2 = $usuarioDb->getUserToEditById($id_destinatario);
    
    $chave_publica_usuario2 = $usuarioDb->getChavePublica($id_destinatario);
    $chave_privada = hex2bin($_GET['chave_privada']);

    $nome1 = $usuario1['nome'];
    $nome2 = $usuario2['nome'];

    var_dump($usuario2);

    $mensagems = $mensagemDb->todasPorConversaAsc($id, $id_destinatario);

    $ky_destinatario_remetente = sodium_crypto_box_keypair_from_secretkey_and_publickey($chave_privada, $chave_publica_usuario2);

    $qtd = count($mensagems);
    foreach($mensagems as $mensagem) {



        
        $msg_metadata = json_decode($mensagem['msg'], true);
        $msg_text = hex2bin($msg_metadata['msg']);
        $nonce = hex2bin($mensagem['nonce']);

        $mensagem_descriptografada = sodium_crypto_box_open(
            $msg_text,
            $nonce,
            $ky_destinatario_remetente
        );

        $nome = ($mensagem['remetente'] == $id) ? $nome1 : $nome2; 
        $linha = $mensagem['hora'] . ' - ' . $nome . ': ' . htmlspecialchars($mensagem_descriptografada, ENT_QUOTES) . "<br/>";

        echo $linha;
    }


} else {
    exit;
}