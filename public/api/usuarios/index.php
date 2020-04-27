<?php 

session_start();
date_default_timezone_set('America/Sao_Paulo');

require '../../../vendor/autoload.php';
require '../../../config.php';


use Dao\UsuarioMySql;
use Classes\Criptografia;

$hora = date("Y-m-d H:i:s");

$method = $_SERVER['REQUEST_METHOD'];
$header = getallheaders();
$error_array = ['error' => 'access deined'];


$usuarioDb = new UsuarioMySql($pdo);
$c = new Criptografia;

switch($method) {
    case 'PUT':
        $parametros = (json_decode(file_get_contents("php://input"), true));
        var_dump($parametros);

    break;

    case 'DELETE':
        $parametros = (json_decode(file_get_contents("php://input"), true));
        $id = filter_var($parametros['id_usuario'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        /**
        * Parametro "id_usuario"
        */

        $usuarioDb->delete($id);

    break;

    case 'POST':
        $parametros = (json_decode(file_get_contents("php://input"), true));

        $login_success_array = [
            'login'=> true,
        ];

        $login_error_array = [
            'login'=> false,
        ];


        $singup_error_array = [
            'singup'=> false,
        ];
        /**
         * Parametros "acao" : "entrar" ou "cadastrar",
         * "username":
         * "senha":
         */


        $acao = $parametros['acao'];

        if($acao == 'sair') {
            unset($_SESSION['id']);
            exit;
        }

        $username = filter_var($parametros['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $senha = $parametros['senha'];
        $id_usuario = md5($username.$hora);

        if($acao == 'entrar' && $username != false) {
            if($usuarioDb->logIn($username, $senha)) {
                $user = $usuarioDb->getUserByUsername($username);
                $_SESSION['id'] = $user['id_user']; 
                $login_success_array['id'] = $_SESSION['id'];

                echo json_encode($login_success_array);
            
            } else {
                echo json_encode($login_error_array);
            }
        } else if($acao == 'cadastrar' && $username != false) {
            $nome = filter_var($parametros['nome'], FILTER_SANITIZE_SPECIAL_CHARS);
            
            $length = strlen($username);
            $aux = 0;

            for($i = 0; $i < $length; $i++) {
                if($username[$i] == ' ')
                    $aux ++;
            }

                if($aux == 0) {
                    if($nome != false) {
                        $usuario = new Classes\Usuario;
                        $c->setParDeChaves();
                        $par_de_chaves = $c->getParDeChaves();
                        $c->setChavePrivada($par_de_chaves);
                        $c->setChavePublica($par_de_chaves);
                        $chave_publica = $c->getChavePublica();
                        $chave_privada = $c->getChavePrivada();


                        $usuario->setId($id_usuario);
                        $usuario->setUsername($username);
                        $usuario->setSenha($senha);
                        $usuario->setUltimoAcesso($hora);
                        $usuario->setEstado(1);
                        $usuario->setNome($nome);
                        $usuario->setChavePublica($chave_publica);
                        $usuario->setImgUrl('/profiles/usuario/profile.jpg');
                        $usuario->setCreatedAt($hora);
                        $usuario->setDescricao("Hey there, i'm using Flowgram!");
                        
                        $result = $usuarioDb->add($usuario);
                    }
                }
            if( isset($result) && $result == true) {
                $_SESSION['id'] = $id_usuario;
                $singup_success_array = [
                    'singup'=> true,
                    'chave_privada' => bin2hex($chave_privada),
                    'id' => $id_usuario
                ];
                
                echo json_encode($singup_success_array);
            } else {

                echo json_encode($singup_error_array);

            }
        }


    break;

    case 'GET':

        if(isset($_GET['id_usuario']))
            echo json_encode($usuarioDb->getUserToEditById($_GET['id_usuario']), true);
        else
            echo json_encode($error_array);
    break;

    default:
        echo json_encode($error_array);
    break;

}