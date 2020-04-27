<?php

session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Login - Flowgram</title>
    <link rel="stylesheet" href="styles/login.css">

    <link rel="stylesheet" href="fontawesome/css/all.css">
</head>
<body>
    <div class="container">
        <form id="form">
            <div class="form-container">
      
                <div class="form-label">
                    <a>Username:</a>
                    <input autocomplete="off" id="username" type="text" name="username" /> 
                </div>
                <div class="form-label">
                    <a>Senha: <i id="olho-senha" class="fas fa-eye-slash"></i></a>
                    <input id="senha" type="password" name="senha" /> 
                </div>
                <div class="form-label">
                    <a>Chave criptográfica privada:<i id="olho-chave"class="fas fa-eye-slash"></i></a>
                    <input id="chave_privada" type="password" name="chave_privada" /> 
                    <p class="info">Não salvamos sua chave privada em nossos servidores, apenas a utilizamos para descriptografar as mensagens. Ainda não tem uma conta? 
                        <a style="
                            font-weight: bold;
                            color: black;
                        " href="cadastrar.php">Cadastre-se</a>
                    </p>
                </div>
                <div class="form-label">

                    <input id="login-btn" type="submit" value="Login">
                </div>
            </div>
        </form>

        <div>


        </div>

    </div>

    <script
  src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script type="text/javascript" src="scripts/login.js"></script>
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->


</body>
</html>
