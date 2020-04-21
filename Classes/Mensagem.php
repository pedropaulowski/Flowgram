<?php

namespace Classes;

class Mensagem {
    private $id;
    private $msg;
    private $remetente;
    private $destinatario;
    private $privacidade;
    private $hora;
    private $estado;
    private $nonce;

    public function setId($id) {
        $this->id = trim($id);
    }

    public function getId() {
        return $this->id;
    }

    public function setMsg($msg) {
        $this->msg = $msg;
    }

    public function getMsg() {
        return $this->msg;
    }

    public function setRemetente($remetente) {
        $this->remetente = trim($remetente);
    }

    public function getRemetente() {
        return $this->remetente;
    }

    public function setDestinatario($destinatario) {
        $this->destinatario = trim($destinatario);
    }

    public function getDestinatario() {
        return $this->destinatario;
    }

    public function setPrivacidade($privacidade) {
        $this->privacidade = $privacidade;
    }

    public function getPrivacidade() {
        return $this->privacidade;
    }

    public function setHora($hora) {
        $this->hora = $hora;
    }

    public function getHora() {
        return $this->hora;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setNonce($nonce) {
        $this->nonce = $nonce;
    }

    public function getNonce() {
        return $this->nonce;
    }
}