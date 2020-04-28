<?php

namespace Socket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Dao\UsuarioMySql;
use Classes\Mensagem;
use Dao\MensagemMySql;
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

        // var_dump($msg);
        switch($real_message['action']) {
            case 'connect':
                $hora = date("Y-m-d H:i:s");
                $id = $real_message['id'];

                $this->clients[$id] = $from;
                $this->clients[$id]->resourceId = $id;
                if($from == $this->clients[$id]) {
                    echo "Iguais\n";
                }
                echo "New connection! ({$from->resourceId})\n";
                echo "New connection! (".$this->clients[$id]->resourceId.")\n";
                
                $this->usuarioDb->ficarOnline($id);
                $this->usuarioDb->setUltimoAcesso($id, $hora);


                $chats = $this->mensagemDb->getUserChats($id);
                $des = hex2bin($real_message['secret_key']);


                foreach($chats as $chat) {

                    $usuario1 = $chat['usuario1'];
                    $usuario2 = $chat['usuario2'];

                    $user_id = ($usuario1 == $id) ? $usuario2 : $usuario1;

                    $user = $this->usuarioDb->getUserById($user_id);

                    $last_msg = $this->mensagemDb->getUltimaDaConversa($usuario1, $usuario2);

                    $last_user = $this->usuarioDb->getUserById($last_msg['remetente']);
                    
                    $msg_metadata = json_decode($last_msg['msg'], true);
                    $msg_text = hex2bin($msg_metadata['msg']);
                    $res = $user['chave_publica'];
                    $nonce = hex2bin($last_msg['nonce']);

                    $ky_destinatario_remetente = sodium_crypto_box_keypair_from_secretkey_and_publickey($des, $res);
    
                    // var_dump(sodium_crypto_box_keypair_from_secretkey_and_publickey($des, $res));
                    $mensagem_descriptografada = sodium_crypto_box_open(
                        $msg_text,
                        $nonce,
                        $ky_destinatario_remetente
                    );
            
                    $msg_metadata['msg'] = $mensagem_descriptografada;


                    $msg = [

                        'action' => 'loadChats',
                        'username' => $user['username'],
                        'id_user' => $user['id_user'],
                        'img_url' => $user['img_url'],
                        'last_user' => $last_user['username'],
                        'hora' => $last_msg['hora'],
                        'last_msg' => htmlspecialchars($mensagem_descriptografada, ENT_QUOTES),
                        'user_estado' => $user['estado']
                    ];
                    
                    $msg = json_encode(json_encode($msg), true);
                    $from->send($msg);
                    //$this->msgToUser($id, $msg);
                    
                }

                

            break;
            case 'disconnect':
                $hora = date("Y-m-d H:i:s");
                $id = $real_message['id'];
                $this->usuarioDb->ficarOffline($id);
                $this->usuarioDb->setUltimoAcesso($id, $hora);
                // var_dump(isset($this->clients[$id]));

                unset($this->clients[$id]);

                // var_dump($this->clients[$id]);

            break;

            case 'message':
                $mensagem = new Mensagem;

                $hora = date("Y-m-d H:i:s");
                $destinatario = $real_message['to'];
                $remetente = $real_message['from'];
                
                $msg_metadata = $real_message['msg'];
                
                if(isset($msg_metadata['msg']) && !empty($msg_metadata['msg'])) {

                    $chave_publica_destinatario = $this->usuarioDb->getChavePublica($destinatario);
                    $chave_privada_remetente = hex2bin($real_message['secret_key']);
                    $ky_remetente_destinatario = sodium_crypto_box_keypair_from_secretkey_and_publickey($chave_privada_remetente, $chave_publica_destinatario);        
                    $nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);

                    $mensagem_criptografada = sodium_crypto_box(
                        $msg_metadata['msg'],
                        $nonce,
                        $ky_remetente_destinatario
                    );

                    $msg_metadata['msg'] = bin2hex($mensagem_criptografada);
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
                    $this->mensagemDb->add($mensagem);
                    $this->usuarioDb->setUltimoAcesso($remetente, $hora);

                    if(isset($this->clients[$destinatario])) {
                        $json_to_destinatario = [
                            'action' => 'need_secret_key',
                            'id_message' => $mensagem->getId(),
                        ];
                        $json_to_destinatario = json_encode(json_encode($json_to_destinatario), true);

                        $this->clients[$destinatario]->send($json_to_destinatario);
                        // $this->clients[$destinatario]->send($json_to_destinatario);
                        // print_r($this->clients[$destinatario], $json_to_destinatario);
                    }
                }
            break;
            case 'secret_key': 
                $id_user = $real_message['id_user'];
                $id_mensagem = $real_message['id_mensagem'];

                $chave_privada = hex2bin($real_message['secret_key']);
                

                $msg_db = $this->mensagemDb->getMensagemById($id_mensagem);
                $msg_metadata = json_decode($msg_db['msg'], true);
                $msg_text = hex2bin($msg_metadata['msg']);
                $nonce = hex2bin($msg_db['nonce']);
                $remetente = $msg_db['remetente'];


                $from_img_url = $this->usuarioDb->getImgUrlById($remetente);

                $des = $chave_privada;
                $res = $this->usuarioDb->getChavePublica($remetente);

                $ky_destinatario_remetente = sodium_crypto_box_keypair_from_secretkey_and_publickey($des, $res);


                $mensagem_descriptografada = sodium_crypto_box_open(
                    $msg_text,
                    $nonce,
                    $ky_destinatario_remetente
                );

                $query = $this->usuarioDb->getUserById($remetente);
                $username = $query['username'];
                $msg_metadata['msg'] = htmlspecialchars($mensagem_descriptografada, ENT_QUOTES);
                $json_to_client = [
                    'action'=> 'New Message',
                    'id_message' => $id_mensagem,
                    'msg' => $msg_metadata,
                    'from' => $remetente,
                    'from_img_url' => $from_img_url,
                    'hora' => $msg_db['hora'],
                    'estado' => 0,
                    'privacidade' =>  $msg_db['privacidade'],
                    'username' => $username
                ];
                
                $json_to_client = json_encode(json_encode($json_to_client), true);

                $this->msgToUser($id_user, $json_to_client);

            break;
            case 'Open chat': 
                $destinatario = $real_message['requester'];
                $remetente = $real_message['other'];
                $pagina = $real_message['pagina'];

                $chave_privada_destinatario = hex2bin($real_message['secret_key']);
                $chave_publica_remetente = $this->usuarioDb->getChavePublica($remetente);



                $mensagens = $this->mensagemDb->get20PorConversaPagination($destinatario, $remetente, $pagina);

                $estado_user = $this->usuarioDb->getEstado($remetente);
                // var_dump($estado_user);
                if(count($mensagens) > 0) {
                    foreach($mensagens as $mensagem) {
                        $msg_metadata = json_decode($mensagem['msg'], true);
                        $msg_text= hex2bin($msg_metadata['msg']);
                        // var_dump($mensagem['hora']);

                        $nonce = hex2bin($mensagem['nonce']);
                        $ky_destinatario_remetente = sodium_crypto_box_keypair_from_secretkey_and_publickey($chave_privada_destinatario, $chave_publica_remetente);
        
                        $mensagem_descriptografada = sodium_crypto_box_open(
                            $msg_text,
                            $nonce,
                            $ky_destinatario_remetente
                        );

                        $msg_metadata['msg'] = htmlspecialchars($mensagem_descriptografada, ENT_QUOTES);
                        



                        if($mensagem['remetente'] != $destinatario) {
                            $this->mensagemDb->lerMensagem($mensagem['id']);
                            $mensagem['estado'] = 1;   
                        }

                        $msg = [
                            'action' => 'msgs_from_chat',
                            'id_message' => $mensagem['id'],
                            'msg' => htmlspecialchars($mensagem_descriptografada, ENT_QUOTES),
                            'hora' => $mensagem['hora'],
                            'from' => $mensagem['remetente'],
                            'estado_message' => $mensagem['estado'],
                            'estado_user' => $estado_user,
                            'id_user' => $remetente
                        ];

                        $msg = json_encode(json_encode($msg), true);

                        $from->send($msg);


                    }
                } else {
                    $estado_user = $this->usuarioDb->getEstado($remetente);
                    
                    $msg = [
                        'action' => 'msgs_from_chat',
                        'id_message' => md5(date('Y-m-d H:i:s')),
                        'msg' => 'Não há conversa ainda',
                        'hora' => 'Do servidor',
                        'from' => $destinatario,
                        'estado_message' => 0,
                        'estado_user' => $estado_user,
                        'id_user' => $remetente

                    ];

                    $msg = json_encode(json_encode($msg), true);

                    $from->send($msg);

                }
            break;
            case 'search_user': 
                
                $this->usuarioDb->ficarOnline($real_message['from']);

                $user = $this->usuarioDb->getUserByUsername($real_message['user']);

                if($user == false) {
                    $msg = [
                        'action' => 'result_user',
                        'result' => false
                    ];

                } else {
                    $msg = [
                        'action' => 'result_user',
                        'result' => true,
                        'username' => $user['username'],
                        'ultimo_acesso' => $user['ultimo_acesso'],
                        'id_user' => $user['id_user'],
                        'img_url' => $user['img_url'],
                        'descricao' => htmlspecialchars($user['descricao'], ENT_QUOTES)
                    ];
                }


                $msg = json_encode(json_encode($msg), true);


                $from->send($msg);


            break;
        }
        
    }

    public function onClose(ConnectionInterface $conn) {
        $key = array_search($conn, $this->clients);
        if ($key) {
            $hora = date("Y-m-d H:i:s");
            $this->clients[$key]->close();
            unset($this->clients[$key]);
            // var_dump($this->clients[$key]);
            $this->usuarioDb->ficarOffline($key);
            $this->usuarioDb->setUltimoAcesso($key, $hora);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    public function msgToUser($id, $msg) {
        
            $this->clients[$id]->send($msg);
    }

    public function unsetOne(ConnectionInterface $conn) {
        unset($conn);
        // var_dump($conn);
    }

}