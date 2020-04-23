<?php

namespace Dao;

use PDO;
use Interfaces\UsuarioDao;

class UsuarioMySql implements UsuarioDao {
    private $pdo;

    public function __construct(PDO $p) {
        $this->pdo = $p;
    }

    public function add(\Classes\Usuario $u) {
        if($this->existeUsername($u->getUsername()) == false) {
            $sql = "INSERT INTO usuarios (
                id_user,
                username,
                ultimo_acesso,
                estado, 
                descricao,
                nome, 
                chave_publica,
                senha,
                img_url,
                created_at
            ) 
            VALUES 
            (
                :id,
                :username,
                :ultimo_acesso,
                :estado,
                :descricao,
                :nome,
                :chave_publica,
                :senha,
                :img_url,
                :created_at
            )";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(":id",$u->getId());
            $sql->bindValue(":username",$u->getUsername());
            $sql->bindValue(":ultimo_acesso",$u->getUltimoAcesso());
            $sql->bindValue(":estado",$u->getEstado());
            $sql->bindValue(":descricao",$u->getDescricao());
            $sql->bindValue(":nome",$u->getNome());
            $sql->bindValue(":chave_publica",$u->getChavePublica());
            $sql->bindValue(":senha",$u->getSenha());
            $sql->bindValue(":img_url",$u->getImgUrl());
            $sql->bindValue(":created_at",$u->getCreatedAt());
            $sql->execute();

            return true;



        } else {
            return false;
        }
    }

    public function logIn($username, $senha) {
        $senhaDB = $this->getSenha($username);
        if($senhaDB != false) {
            $sql = "SELECT * FROM usuarios WHERE username = :username";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(":username", $username);
            $sql->execute();

            $sql = $sql->fetch();

            if(password_verify($senha, $senhaDB)) 
                return true;
            else 
                return false;
        } else {
            return false;
        }
        
    }

    public function getSenha($username){
        $sql = "SELECT * FROM usuarios WHERE username = :username";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":username", $username);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $sql = $sql->fetch();

            return $sql['senha'];
        } else {
            return false;
        }

    }

    public function existeUsername($username) {
        $sql = "SELECT * FROM usuarios WHERE username = :username";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":username", $username);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM usuarios WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        return true;
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM usuarios WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return $sql->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getUserByUsername($username) {

        $sql = "SELECT * FROM usuarios WHERE username = :username";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":username", $username);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $sql = $sql->fetch(PDO::FETCH_ASSOC);
            return $sql;
        } else {
            return false;
        }
    }

    public function getUltimoAcesso($id) {
        $sql = "SELECT * FROM usuarios WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $sql = $sql->fetch(PDO::FETCH_ASSOC);
            return $sql['ultimo_acesso'];
        } else {
            return false;
        }
    }

    public function setUltimoAcesso($id, $ultimo_acesso) {
        $sql = "UPDATE usuarios SET ultimo_acesso = :ultimo_acesso WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":ultimo_acesso", $ultimo_acesso);
        $sql->bindValue(":id", $id);

        $sql->execute();

        return true;

    }

    public function ficarOffline($id) {
        $sql = "UPDATE usuarios SET estado = 0 WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);

        $sql->execute();

        return true;
    }

    public function getChavePublica($id) {
        $sql = "SELECT * FROM usuarios WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $sql = $sql->fetch(PDO::FETCH_ASSOC);
            return $sql['chave_publica'];
        } else {
            return false;
        }
    }

    public function getImgUrlById($id) {
        $sql = "SELECT * FROM usuarios WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $sql = $sql->fetch(PDO::FETCH_ASSOC);
            return $sql['img_url'];
        } else {
            return false;
        }
    }

    public function ficarOnline($id) {
        $sql = "UPDATE usuarios SET estado = 1 WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);

        $sql->execute();

        return true;
    }

    public function getEstado($id) {
        $sql = "SELECT * FROM usuarios WHERE id_user = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $sql = $sql->fetch(PDO::FETCH_ASSOC);
            return $sql['estado'];
        } else {
            return false;
        }
    }

    
}