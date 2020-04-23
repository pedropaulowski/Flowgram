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
                    <div id="dropdown-menu" class="dropdown-menu">
                        <div class="menu-options">
                            <div>Segurança</div>
                            <div>Solicitar dados da conta</div>
                            <div>Meu Perfil</div>
                            <div>Sair</div>
                        </div>
                    </div>
                </div>
                <h1 class="">Flowgram</h1>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Buscar" id="search-text">
                <a id="buscar"class="btn-search"><i class="fas fa-search"></i></i></a>
            </div>

            <div id="chats-list" class="chats-list">
                
            </div>
        </div>

        <div class="chat">
            <div class="header-chat">
                <a id="chat-username" user="id_user" class="chat-username"></a>
                <a id="username-status" class="username-status"></a>
            </div>
            <div id="content-chat" class="content-chat">
                <!--<div class="card-message-other">
                    <div class="message-other-text">
                        Oi, seu pãozão, que tal 
                        sairmos para tomar uma chícara de café?
                        Que tal? Que tal? Que tal? Que tal? Que tal? 
                        Que tal? Que tal? Que tal? Que tal? Que tal?
                        Que tal? Que tal? Que tal? Que tal? Que tal?
                    </div>
                    <div class="message-other-date">
                        20:18:15
                    </div>
                </div>

                <div class="card-message-me">
                    <div class="message-me-text">
                        Oi, seu pãozão, que tal 
                        sairmos para tomar uma chícara de café?
                        Que tal? Que tal? Que tal? Que tal? Que tal? 
                        Que tal? Que tal? Que tal? Que tal? Que tal?
                        Que tal? Que tal? Que tal? Que tal? Que tal?
                    </div>
                    <div class="message-me-date">
                        20:18:15
                        Não lida 
                        <a class="read"><i class="far fa-check-circle"></i></a>
                     Lida 
                        <a><i class="fas fa-check-circle"></i></a>
                    </div>
                </div>
                -->

            </div>

            <div class="footer-chat">
                <div class="message-bar">
                    <textarea type="text" placeholder="Mensagem" id="message-text" ></textarea>
                    <a id="send-message"class="btn-send"><i class="fas fa-paper-plane"></i></a>
                </div>
            </div>
        </div>
    </div>    
    
    <script
    src="https://code.jquery.com/jquery-3.5.0.min.js"
    integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
    crossorigin="anonymous"></script>

    <script type="text/javascript" src="scripts/index.js"></script>
    <script type="text/javascript" src="scripts/socket.js"></script>
    <script type="text/javascript" src="scripts/menu.js"></script>


</body>
</html>