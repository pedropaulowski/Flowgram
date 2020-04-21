<?php

namespace Flowgram;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Classes\Usuario;
use Dao\UsuarioMySql;
use Classes\Mensagem;
use Dao\MensagemMySql;
use Classes\Criptografia;
use PDO;
date_default_timezone_set('America/Sao_Paulo');



class Socket implements MessageComponentInterface {
    private $clients = [];
    private $usuarioDb;
    private $mensagemDb;

    public function __construct(PDO $pdo) {
        $this->usuarioDb = new UsuarioMySql($pdo);
        $this->mensagemDb = new MensagemMySql($pdo);
    }
    public function onOpen(ConnectionInterface $conn) {

        // Store the new connection in $this->clients
        $this->clients[$conn->resourceId] = $conn;

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $real_message = json_decode(json_decode($msg), true);

        switch($real_message['action']) {
            case 'connect':
                $hora = date("Y-m-d H:i:s");
                $id = $real_message['id'];
                $from->resourceId = $id;
                $this->clients[$from->resourceId] = $id;
                $this->usuarioDb->ficarOnline($id);
                $this->usuarioDb->setUltimoAcesso($id, $hora);

            break;
            case 'disconnect':
                $hora = date("Y-m-d H:i:s");
                $id = $real_message['id'];
                $this->clients[$from->resourceId] = '';
                $this->usuarioDb->ficarOffline($id);
                $this->usuarioDb->setUltimoAcesso($id, $hora);

            break;

            case 'message':
                $mensagem = new Mensagem;
                $criptografia = new Criptografia;

                $hora = date("Y-m-d H:i:s");
                $destinatario = $real_message['to'];
                $remetente = $real_message['from'];
                
                $msg_metadata = $real_message['msg'];
                
                $chave_publica_destinatario = $this->usuarioDb->getChavePublica($destinatario);
                $chave_privada_remetente = hex2bin($real_message['chave_privada']);
                
                $nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);

                $mensagem_criptografada = bin2hex($criptografia->criptografarMensagem($msg_metadata['msg'], $chave_privada_remetente, $chave_publica_destinatario, $nonce));
                $msg_metadata['msg'] = $mensagem_criptografada;
                $privacidade = $real_message['privacidade'];
                $estado = 0;


                $mensagem->setId(md5($hora.$destinatario.$remetente));
                $mensagem->setMsg(json_encode($msg_metadata, true));
                $mensagem->setRemetente($remetente);
                $mensagem->setDestinatario($destinatario);
                $mensagem->setPrivacidade(json_encode($privacidade, true));
                $mensagem->setHora($hora);
                $mensagem->setEstado($estado);
                $mensagem->setNonce(bin2hex($nonce));
                $this->mensagemDb->add($this->mensagem);
                $this->usuarioDb->setUltimoAcesso($remetente, $hora);

                if(isset($this->clients[$destinatario])) {
                    $json_to_destinatario = [
                        'action' => 'new message',
                        'from' => $remetente,
                        'id' => $mensagem->getId(),
                        'msg' => $msg_metadata,
                    ];

                }
            break;
        }
        
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    public function msgToUser($id, $msg) {
        if(isset($this->clients[$id])) {
            $this->clients[$id]->send($msg);
        } else {
            //store in DataBase
        }
    }
}