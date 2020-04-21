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
                <a class="btn-config"><i class="fas fa-bars"></i></a>
                <h1 class="">Flowgram</h1>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Buscar" id="search-text">
                <a id="buscar"class="btn-search"><i class="fas fa-search"></i></i></a>
            </div>

            <div class="chats-list">
                <div class="chat-card">

                   
                    <div id="img-username" class="chat-img-circle">
                        <img class="img-profile" src="profiles/usuario/profile.jpg" alt="">
                    </div>
                    <div class="chat-info">
                        <div class="chat-info-top">
                            <a class="msg-username">Joca</a>
                            <a class="msg-date">19:04</a>
                        </div>
                        <div class="chat-info-bot">
                            <a class="last-user">Joca:</a>
                            <a class="last-msg"> 
                                Olá você me parece muito mito seu mito demais, 
                                mais texto mais texto desnecessário para eu fazer a parada direito
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="chat">
            <div class="header-chat">
                <a class="chat-username">Joca</a>
                <a class="username-status">Online</a>
            </div>
            <div id="content-chat" class="content-chat">
                <div class="card-message-other">
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
                        <!-- Não lida -->
                        <a class="read"><i class="far fa-check-circle"></i></a>
                        <!-- Lida -->
                        <a><i class="fas fa-check-circle"></i></a>
                    </div>
                </div>



            </div>

            <div class="footer-chat">
                <div class="message-bar">
                    <textarea type="text" placeholder="Mensagem" id="message-text" ></textarea>
                    <a id="send-message"class="btn-send"><i class="fas fa-paper-plane"></i></a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="scripts/index.js"></script>

</body>
</html>