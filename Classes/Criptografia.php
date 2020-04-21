<?php

namespace Classes;


class Criptografia {
    private $par_de_chaves;
    private $chave_publica;
    private $chave_privada;


    public function setParDeChaves() {
        $this->par_de_chaves = sodium_crypto_box_keypair();
    }

    public function getParDeChaves() {
        return $this->par_de_chaves;
    }

    public function setChavePublica($par_de_chaves) {
        $this->chave_publica = sodium_crypto_box_publickey($par_de_chaves);
    }

    public function getChavePublica() {
        return $this->chave_publica;
    }

    public function setChavePrivada($par_de_chaves) {
        $this->chave_privada = sodium_crypto_box_secretkey($par_de_chaves);
    }

    public function getChavePrivada() {
        return $this->chave_privada;
    }

    public function criptografarMensagem($mensagem, $chave_privada_remetente, $chave_publica_destinatario, $nonce) {
        $remetente_destinatario_kp = sodium_crypto_box_keypair_from_secretkey_and_publickey($chave_privada_remetente, $chave_publica_destinatario);


        $mensagem_criptografada = sodium_crypto_box(
            $mensagem,
            $nonce,
            $remetente_destinatario_kp
        );

        return $mensagem_criptografada;

    }

    public function descriptografarMensagem($mensagem, $chave_privada_destinatario, $chave_publica_remetente, $nonce) {
        $destinatario_remetente_kp = sodium_crypto_box_keypair_from_secretkey_and_publickey($chave_privada_destinatario, $chave_publica_remetente);


        $mensagem_descriptografada = sodium_crypto_box_open (
            $mensagem,
            $nonce,
            $destinatario_remetente_kp
        );

        return $mensagem_descriptografada;

    }
}


/*FUNCIONAMENTO BASICO DA CRIPTOGRAFIA
$c = new Criptografia;
$c->setParDeChaves();
$par_de_chaves1 = $c->getParDeChaves();
$c->setChavePublica($par_de_chaves1);
$c->setChavePrivada($par_de_chaves1);
$chave_publica1 = $c->getChavePublica();
$chave_privada1 = $c->getChavePrivada();

$mensagem = "Olá, mastodonte!";



$d = new Criptografia();
$d->setParDeChaves();
$par_de_chaves2 = $d->getParDeChaves();
$d->setChavePublica($par_de_chaves2);
$d->setChavePrivada($par_de_chaves2);
$chave_publica2 = $d->getChavePublica();
$chave_privada2 = $d->getChavePrivada();


$nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);


$mensagem_criptografada = $c->criptografarMensagem($mensagem, $chave_privada2, $chave_publica1, $nonce);

echo $mensagem_criptografada;
echo "<br/>";
$mensagem_descriptografada = $c->descriptografarMensagem($mensagem_criptografada, $chave_privada1, $chave_publica2, $nonce);
echo $mensagem_descriptografada;

*/