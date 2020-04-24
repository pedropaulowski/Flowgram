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
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
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
        </div>

        <div class="chat">
            <div class="header-chat">
                <a id="chat-username" user="id_user" class="chat-username"></a>
                <a id="username-status" class="username-status"></a>
            </div>
            <div id="content-chat" class="content-chat">
                <div class="profile">
                    <form id="form-edit"method="POST" enctype="multipart/form-data">
                        <div  class="profile-img-circle">
                            <img id="open-img-input"class="img-my-profile" src="http://flowgram.test/profiles/usuario/profile.jpg" alt="">
                        </div>
                        <div class="edit-username">
                            <input type="file" name="file" class="input-profile" id="file"/>
                        </div>
                        <a>Username:</a>
                        <div class="edit-username">
                            <input autocomplete="off" name="username" type="text" class="input-profile" value="${username}"id="username-text"/>
                        </div>
                        <a>Nome:</a>
                        <div class="edit-name">
                            <input autocomplete="off" name="name" type="text" class="input-profile"  value="${Name}"id="name-text"/>
                        </div>
                        <a>Recado:</a>
                        <div class="edit-descripton">
                            <input autocomplete="off" name="description" type="text" class="input-profile"  value="${Name}"id="description-text"/>
                        </div>
                        
                        <button type="submit"id="editar"><i class="fas fa-save"></i></button>

                    </form>
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
    
    <script
    src="https://code.jquery.com/jquery-3.5.0.min.js"
    integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
    crossorigin="anonymous"></script>
    <script type="text/javascript" src="scripts/axios.min.js"></script>
    <script type="text/javascript" src="scripts/index.js"></script>
    <script type="text/javascript" src="scripts/socket.js"></script>
    <script type="text/javascript" src="scripts/menu.js"></script>


</body>
</html>