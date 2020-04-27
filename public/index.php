<?php
session_start();

if(!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Flowgram</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="fontawesome/css/all.css"> 

</head>
<body>
    <div class="container-flex ">
        <div class="chats">
            <div class="header">
                <div id="menu" class="btn-config"><i class="fas fa-bars"></i>

                </div>
                <h1 class="">Flowgram</h1>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Buscar" id="search-text">
                <a id="buscar"class="btn-search"><i class="fas fa-search"></i></a>
            </div>

            <div id="dropdown-menu" class="dropdown-menu">
                <div class="menu-options">
                    <div><a data-toggle="security" class="btn-menu"><i class="fas fa-lock"></i>Segurança</a></div>
                    <div><a data-toggle="dados" class="btn-menu"><i class="fas fa-database"></i>Solicitar dados da conta</a></div>
                    <div><a data-toggle="estatistica" class="btn-menu"><i class="fas fa-chart-pie"></i>Estatísticas</a></div>
                    <div><a data-toggle="perfil" class="btn-menu"><i class="fas fa-user-alt"></i>Meu perfil</a></div>
                    <div id="log-out"><a><i class="fas fa-sign-out-alt"></i>Sair</a></div>
                </div>
            </div>

            <div id="chats-list" class="chats-list">

            </div>
            <div onclick="conversas()" id="handle-chats-list">
                <a>Ocultar/Mostrar Conversas<i class="fas fa-caret-down"></i></a>
            </div>
        </div>

        <div class="chat">
            <div class="header-chat">
                    <a id="chat-username" user="id_user" class="chat-username"></a>
                    <a id="username-status" class="username-status"></a>

            </div>
            <div id="content-chat" class="content-chat">


            </div>
            <div class="footer-chat">
                <div id="emoji-menu" class="emoji-menu">
                    <div id="emoji-header" class="emoji-header">

                    </div>
                    <div id="emoji-content" class="emoji-content">

                    </div>

                </div>
                <div class="message-bar">
                    <textarea type="text" placeholder="Mensagem" id="message-text" ></textarea>
                    <a id="send-message"class="btn-send"><i class="fas fa-paper-plane"></i></a>
                    <a id="btn-emoji" onclick="emojiArea()" class="btn-send" ><i class="far fa-surprise"></i></a>
                    <a target="_blank" id="export-chat" class="btn-send"><i class="fas fa-download"></i></a>
                </div>


            </div>
        </div>

    </div>    
    
    <script
    src="https://code.jquery.com/jquery-3.5.0.min.js"
    integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
    crossorigin="anonymous"></script>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="scripts/index.js"></script>
    <script type="text/javascript" src="scripts/socket.js"></script>
    <script type="text/javascript" src="scripts/menu.js"></script>


    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <script type="text/javascript" src="scripts/emojis.js"></script>



</body>
</html>