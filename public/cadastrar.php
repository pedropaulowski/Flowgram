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
    <link rel="stylesheet" href="fontawesome/css/all.css">
</head>
<body>
    <div class="container">
        <form id="form">
            <div class="form-container">
                <div id="chave-privada" style="display:none;" class="form-label">
                    <p class="info">
                        Este é o único momento que nós podemos lhe oferecer sua chave privada, 
                        pegue-a agora, pois no futuro será necessária para fazer log in.
                        <a style="
                            font-weight: bold;
                            color: black;
                        " href="login.php">Log in</a>
                    </p>
                    <a>Chave privada:</a>
                    <input autocomplete="off" type="text" name="chave" id="chave-input"/>
                    <button onclick="copiarChave()"class="copy-btn">Copiar</button>

                </div>
                <div class="form-label">
                    <a>Username:</a>
                    <input autocomplete="off" type="text" name="username" id="username"/> 
                    <p class="info">
                        Não são permitidos caractéres especiais, 
                        <br/>apensas ['a' até 'z'] + ['_', '.'] 
                    </p>
                </div>

                <div class="form-label">
                    <a>Nome:</a>
                    <input autocomplete="off" type="text" name="Nome" id="nome"/> 
                </div>
  
                <div class="form-label">
                    <a>Senha: <i id="olho-senha" class="fas fa-eye-slash"></i></a>
                    <input id="senha" type="password" name="senha" /> 
                </div>

                <div class="form-label" style="
                    flex-direction: row;
                    justify-content: space-between;
                    align-items: center;">

                    <input id="login-btn" type="submit" value="Cadastrar">
                    <p class="info">Já tem uma conta? Faça 
                        <a style="
                            font-weight: bold;
                            color: black;
                        " href="login.php">Log in</a>
                    </p>
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
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->


</body>
</html>
