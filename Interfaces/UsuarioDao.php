<?php 

namespace Interfaces;

use Classes\Usuario;

interface UsuarioDao {
    public function add(Usuario $u);
    public function logIn($username, $senha);
    public function existeUsername($username);
    public function delete($id);
    
    public function getUserById($id);
    public function getUserToEditById($id);
    public function getUserByUsername($username);
    
    public function setUltimoAcesso($id, $ultimo_acesso);
    public function getUltimoAcesso($id);
    
    public function getSenha($username);
    
    public function getChavePublica($id);
    public function getImgUrlById($id);

    public function ficarOnline($id);
    public function ficarOffline($id);

    public function getEstado($id);

    public function editUser($id, $img_url = 0, $username, $nome, $descricao);

}