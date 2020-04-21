<?php

namespace Classes;

class Usuario {
    private $id;
    private $nome;
    private $username;
    private $ultimo_acesso;
    private $estado;
    private $descricao;
    private $chave_publica;
    private $chave_privada = 0;
    private $senha;
    private $img_url;
    private $created_at;


     
    public function getCreatedAt() {
        return $this->created_at;
    }
 
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;

    }
 
    public function getImgUrl() {
        return $this->img_url;
    }

    public function setImgUrl($img_url) {
        $this->img_url = trim($img_url);
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = password_hash(trim($senha), PASSWORD_BCRYPT);

    }

 
    public function getChavePublica() {
        return $this->chave_publica;
    }

    public function setChavePublica($chave_publica) {
        $this->chave_publica = $chave_publica;

    }

    public function getChavePrivada() {
        return $this->chave_privada;
    }

    public function setChavePrivada($chave_privada) {
        $this->chave_privada = $chave_privada;

    }

 
    public function getDescricao() {
        return $this->descricao;
    }

 
    public function setDescricao($descricao) {
        $this->descricao = trim($descricao);

    }


    public function getEstado() {
        return $this->estado;
    }

 
    public function setEstado($estado) {
        $this->estado = $estado;
    }


    public function getUltimoAcesso() {
        return $this->ultimo_acesso;
    }

 
    public function setUltimoAcesso($ultimo_acesso) {
        $this->ultimo_acesso = $ultimo_acesso;

    }

 
    public function getUsername() {
        return $this->username;
    }


    public function setUsername($username) {
        $this->username = trim($username);

    }


    public function getNome() {
        return $this->nome;
    }


    public function setNome($nome) {
        $this->nome = trim(ucwords($nome));

    }


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = trim($id);

    }
}