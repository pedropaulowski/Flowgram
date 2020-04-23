<?php
session_start();

if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Cadastrar - Flowgram</title>
    <link rel="stylesheet" href="styles/cadastrar.css">
        <!-- <link rel="stylesheet" href="fontawesome/css/all.css"> -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
        <form id="form">
            <div class="form-container">
      
                <div class="form-label">
                    <a>Username:</a>
                    <input autocomplete="off" type="text" name="username" id="username"/> 
                </div>

                <div class="form-label">
                    <a>Nome:</a>
                    <input autocomplete="off" type="text" name="Nome" id="nome"/> 
                </div>
            
                <div class="form-label">
                    <a>Senha: <i id="olho-senha" class="fas fa-eye-slash"></i></a>
                    <input id="senha" type="password" name="senha" /> 
                </div>

                <div class="form-label">

                    <input id="login-btn" type="submit" value="Cadastrar">
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

    <script type="text/javascript" src="scripts/cadastrar.js"></script>

</body>
</html>
