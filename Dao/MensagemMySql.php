<?php

namespace Dao;

use PDO;
use Interfaces\MensagemDao;

class MensagemMySql implements MensagemDao {
    private $pdo;

    public function __construct(PDO $p) {
        $this->pdo = $p;
    }

    public function add(\Classes\Mensagem $m) {
        $usuarios = [
            $m->getDestinatario(), 
            $m->getRemetente()
        ];


        $sql = "INSERT INTO mensagens (
            id,
            msg,
            remetente,
            destinatario, 
            privacidade,
            hora, 
            estado,
            nonce
            
        ) 
        VALUES 
        (
            :id,
            :msg,
            :remetente,
            :destinatario, 
            :privacidade,
            :hora, 
            :estado,
            :nonce

        )";

        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $m->getId());
        $sql->bindValue(":msg", $m->getMsg());
        $sql->bindValue(":remetente", $m->getRemetente());
        $sql->bindValue(":destinatario", $m->getDestinatario());
        $sql->bindValue(":privacidade", $m->getPrivacidade());
        $sql->bindValue(":hora", $m->getHora());
        $sql->bindValue(":estado", $m->getEstado());
        $sql->bindValue(":nonce", $m->getNonce());
        $sql->execute();
        
        if($this->existeChat($usuarios[0], $usuarios[1]) == false)
            $this->criarChat($usuarios[0], $usuarios[1]);

        return true;

    }

    public function todasPorConversa($usuario1, $usuario2) {
        $sql = "SELECT * FROM mensagens WHERE 
        (remetente = :usuario1 AND destinatario = :usuario2) OR
        (remetente = :usuario2 AND destinatario = :usuario1) ORDER BY hora DESC";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":usuario1", $usuario1);
        $sql->bindValue(":usuario2", $usuario2);
        
        $sql->execute();

        if($sql->rowCount() > 0) {
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function get20PorConversaPagination($usuario1, $usuario2, $pagina) {
        $pagina = ($pagina == 0) ? $pagina : $pagina * 25;
        var_dump($pagina);
        
        $sql = "SELECT * FROM mensagens WHERE 
        (remetente = :usuario1 AND destinatario = :usuario2) OR
        (remetente = :usuario2 AND destinatario = :usuario1) ORDER BY hora DESC LIMIT $pagina,25";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":usuario1", $usuario1);
        $sql->bindValue(":usuario2", $usuario2);
        
        $sql->execute();

        if($sql->rowCount() > 0) {
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function getUltimaDaConversa($usuario1, $usuario2) {

        $sql = "SELECT * FROM mensagens WHERE 
        (remetente = :usuario1 AND destinatario = :usuario2) OR
        (remetente = :usuario2 AND destinatario = :usuario1) ORDER BY hora DESC LIMIT 1";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":usuario1", $usuario1);
        $sql->bindValue(":usuario2", $usuario2);
        
        $sql->execute();

        if($sql->rowCount() > 0) {
            return $sql->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function getNovasMensagensConversa($usuario1, $usuario2, $ultimo_acesso) {
        $sql = "SELECT * FROM mensagens WHERE 
        (remetente = :usuario1 AND destinatario = :usuario2) OR
        (remetente = :usuario2 AND destinatario = :usuario1) WHERE hora > :ultimo_acesso";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":usuario1", $usuario1);
        $sql->bindValue(":usuario2", $usuario2);
        $sql->bindValue(":hora", $ultimo_acesso);

        
        $sql->execute();

        if($sql->rowCount() > 0) {
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function getAntigasMensagensConversa($usuario1, $usuario2, $ultimo_acesso) {
        $sql = "SELECT * FROM mensagens WHERE 
        (remetente = :usuario1 AND destinatario = :usuario2) OR
        (remetente = :usuario2 AND destinatario = :usuario1) WHERE hora <=:ultimo_acesso";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":usuario1", $usuario1);
        $sql->bindValue(":usuario2", $usuario2);
        $sql->bindValue(":hora", $ultimo_acesso);

        
        $sql->execute();

        if($sql->rowCount() > 0) {
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function countNovasMensagens($destinatario, $ultimo_acesso) {
        $sql = "SELECT * FROM mensagens WHERE destinatario = :destinatario 
        WHERE hora >: ultimo_acesso";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":destinatario", $destinatario);
        $sql->bindValue(":hora", $ultimo_acesso);

        
        $sql->execute();

        if($sql->rowCount() > 0) {
            return $sql->rowCount();
        } else {
            return 0;
        }
    }

    public function countNovasMensagensConversa($usuario1, $usuario2, $ultimo_acesso) {
        $sql = "SELECT * FROM mensagens WHERE 
        (remetente = :usuario1 AND destinatario = :usuario2) OR
        (remetente = :usuario2 AND destinatario = :usuario1) WHERE hora > :ultimo_acesso";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":usuario1", $usuario1);
        $sql->bindValue(":usuario2", $usuario2);
        $sql->bindValue(":hora", $ultimo_acesso);

        
        $sql->execute();

        if($sql->rowCount() > 0) {
            return $sql->rowCount();
        } else {
            return 0;
        }
    }

    public function lerMensagem($id) {
        $sql = "UPDATE mensagens SET estado = 1 WHERE id = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($this->mensagemFoiLida($id) == true) 
            return true;
        else 
            return false;

    }

    public function mensagemFoiLida($id) {
        $sql = "SELECT * FROM mensagens WHERE id = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();
        
        $sql = $sql->fetch(PDO::FETCH_ASSOC);

        if($sql['estado'] == 1) 
            return true;
        else 
            return false;
    }

    public function getMensagemById($id){
        $sql = "SELECT * FROM mensagens WHERE id = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();
        
        $sql = $sql->fetch(PDO::FETCH_ASSOC);

        return $sql;
    }

    public function getUserChats($id) {
        $sql = "SELECT * FROM chats WHERE usuario1 = :id OR usuario2 = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();
        
        if($sql->rowCount() > 0) {
            $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $sql;
        } else {
            return [];
        }
    }

    public function existeChat($usuario1, $usuario2) {
        $sql = "SELECT * FROM chats WHERE 
        (usuario1 = :usuario1 AND usuario2 = :usuario2) 
        OR 
        (usuario2 = :usuario1 AND usuario1 = :usuario2)";

        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":usuario1", $usuario1);
        $sql->bindValue(":usuario2", $usuario2);
        $sql->execute();
        
        if($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function criarChat($usuario1, $usuario2) {
        $usuarios = [
            0=> $usuario1,
            1=> $usuario2
        ];
        sort($usuarios);
        var_dump($usuarios);


        $sql = "INSERT INTO chats (usuario1, usuario2) VALUES (:usuario1, :usuario2)";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":usuario1", $usuarios[0]);
        $sql->bindValue(":usuario2", $usuarios[1]);
        $sql->execute();

    }
}