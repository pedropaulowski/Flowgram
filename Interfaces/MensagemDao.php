<?php

namespace Interfaces;

use Classes\Mensagem;

interface MensagemDao {
    public function add(Mensagem $m);
    
    public function todasPorConversa($usuario1, $usuario2);
    public function get20PorConversaPagination($usuario1, $usuario2, $pagina);
    public function getUltimaDaConversa($usuario1, $usuario2);
    public function getNovasMensagensConversa($usuario1, $usuario2, $ultimo_acesso);
    public function getAntigasMensagensConversa($usuario1, $usuario2, $ultimo_acesso);
    
    public function countNovasMensagensConversa($usuario1, $usuario2, $ultimo_acesso);
    public function countNovasMensagens($destinatario, $ultimo_acesso);
    
    public function lerMensagem($id);
    public function mensagemFoiLida($id);
    public function getMensagemById($id);

    public function getUserChats($id);
    public function existeChat($usuario1, $usuario2);
    public function criarChat($usuario1, $usuario2);

    public function todasPorConversaAsc($id, $id_destinatario);
}