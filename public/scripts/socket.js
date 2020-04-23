const socket = new WebSocket("ws://flowgram-messenger.herokuapp.com:8080")
const my_id = localStorage.getItem('id')
const secret_key = localStorage.getItem('chave')
var aux = 0


socket.onopen = () =>{
    var msg = {
        action : "connect",
        id: my_id,
        secret_key: secret_key
    }
    msg = JSON.stringify(JSON.stringify(msg))
    socket.send(msg)
}

var btn_send = document.getElementById('send-message')

if(btn_send != null) {
    btn_send.onclick = () => {   
        var msg_text = document.getElementById('message-text').value
        var id_destinatario = document.getElementById('chat-username').getAttribute('id_user')
        if(msg_text != '') {
            var json = {
                action: "message",
                to: id_destinatario,
                from: my_id,
                msg: {
                    type: "text", //por enquanto apenas mensagens de texto
                    msg: msg_text
                },
                privacidade: {
                    tipo: "individual"
                },
                estado: 0,
                secret_key: secret_key
            }

            json = JSON.stringify(JSON.stringify(json))
            hora = retornaDataAtual()
            var html = createCardMe(Math.floor(Math.random() * 1000), encodeHTML(msg_text), hora, 0)
            socket.send(json)
            document.getElementById('message-text').value = ''
            $('#content-chat').append(html)
            barra();
        }
    }
}


socket.onmessage = (e) => {
    console.log(e)
    var socket_msg = JSON.parse( JSON.parse( e.data ) )

    console.log(socket_msg)

    switch(socket_msg.action) {
        case 'need_secret_key':
            var msg = {
                action : "secret_key",
                id_user: my_id,
                id_mensagem : socket_msg.id_message,
                secret_key : secret_key
            }
            
            msg = JSON.stringify(JSON.stringify(msg))
            
            socket.send(msg)
        break;

        case 'New Message':
            var current_chat = document.getElementById('chat-username').getAttribute('id_user')
            if(current_chat == socket_msg.from) {
                var html_msg = createCardOther(socket_msg.id_message, socket_msg.msg.msg, socket_msg.hora)
            
                $('#content-chat').append(html_msg)
                barra();
            } else {
                if (searchChatByUsername == true) {
                    $(`#chat${socket_msg.username}`).remove()

                    console.log($(`#chat${socket_msg.username}`))
                    var html_chat_card = createChatCard(socket_msg.from_img_url, socket_msg.username, socket_msg.hora, socket_msg.username, socket_msg.msg.msg, 1)
                    
                    $('#chats-list').prepend(html_chat_card)

                    $(`#chat${socket_msg.username}`).click(function() {

                        var id_div = `chat${socket_msg.username}`
                        if(document.getElementById(id_div).getAttribute('username') != document.getElementById('chat-username').innerText) {
                            
                            
        
                            var msg = {
                                action: 'Open chat',
                                requester: my_id,
                                other: socket_msg.from,
                                secret_key: secret_key,
                                pagina: 0
                            }
                            document.getElementById('content-chat').innerHTML = ''
        
                            msg = JSON.stringify(JSON.stringify(msg))
        
                            socket.send(msg)
                            
                            document.getElementById('chat-username').innerText = socket_msg.username
        
                                document.getElementById('chat-username').setAttribute('id_user', socket_msg.from)
                                $('#username-status').innerHTML = socket_msg.user_estado
        
        
                            setTimeout(barra, 1000);
                        }
                    })
                } else {
                    $(`#chat${socket_msg.username}`).remove()
                    var html_chat_card = createChatCard(socket_msg.from_img_url, socket_msg.username, socket_msg.hora, socket_msg.username, socket_msg.msg.msg, 1)
                    $('#chats-list').prepend(html_chat_card)

                    $(`#chat${socket_msg.username}`).click(function() {

                        var id_div = `chat${socket_msg.username}`
                        if(document.getElementById(id_div).getAttribute('username') != document.getElementById('chat-username').innerText) {
                            
                            
        
                            var msg = {
                                action: 'Open chat',
                                requester: my_id,
                                other: socket_msg.from,
                                secret_key: secret_key,
                                pagina: 0
                            }
                            document.getElementById('content-chat').innerHTML = ''
        
                            msg = JSON.stringify(JSON.stringify(msg))
        
                            socket.send(msg)
                            
                            document.getElementById('chat-username').innerText = socket_msg.username
        
                                document.getElementById('chat-username').setAttribute('id_user', socket_msg.from)
                                $('#username-status').innerHTML = socket_msg.user_estado
        
        
                            setTimeout(barra, 1000);
                        }
                    })
                }
            }


        break;

        case 'loadChats':

            var html_msg = createChatCard(
                socket_msg.img_url,
                socket_msg.username,
                socket_msg.hora,
                socket_msg.last_user,
                socket_msg.last_msg                
            )

            
            $('#chats-list').append(html_msg)
            $(`#chat${socket_msg.username}`).click(function() {

                var id_div = `chat${socket_msg.username}`
                if(document.getElementById(id_div).getAttribute('username') != document.getElementById('chat-username').innerText) {
                    
                    

                    var msg = {
                        action: 'Open chat',
                        requester: my_id,
                        other: socket_msg.id_user,
                        secret_key: secret_key,
                        pagina: 0
                    }
                    document.getElementById('content-chat').innerHTML = ''

                    msg = JSON.stringify(JSON.stringify(msg))

                    socket.send(msg)
                    
                    document.getElementById('chat-username').innerText = socket_msg.username

                        document.getElementById('chat-username').setAttribute('id_user', socket_msg.id_user)
                        $('#username-status').innerHTML = socket_msg.user_estado


                    setTimeout(barra, 1000);
                }
            })
        break;

        case 'msgs_from_chat':

            

            var html_card_msg = (socket_msg.from != my_id) ? createCardOther(socket_msg.id_message, socket_msg.msg, socket_msg.hora) : createCardMe(socket_msg.id_message, socket_msg.msg, socket_msg.hora, socket_msg.estado_message)
            $('#username-status').html( (socket_msg.estado_user == 0) ? 'Offline' : 'Online' )
            $('#content-chat').prepend(html_card_msg)

        break;

        case 'result_user':
            var chats_list = document.getElementById('chats-list')
            if(socket_msg.result == true) {
                var html = createCardSearch(socket_msg.img_url, socket_msg.username, socket_msg.ultimo_acesso, socket_msg.descricao)
                $('#chats-list').prepend(html)

                $(`#result-card`).click(function() {
                    
                    var id_div = `result-card`
                    if(document.getElementById(id_div).getAttribute('username') != document.getElementById('chat-username').innerText) {
                        console.log(document.getElementById(id_div).getAttribute('username'))
                        var msg = {
                            action: 'Open chat',
                            requester: my_id,
                            other: socket_msg.id_user,
                            secret_key: secret_key,
                            pagina: 0
                        }

    
                        msg = JSON.stringify(JSON.stringify(msg))
    
                        socket.send(msg)

                        document.getElementById('content-chat').innerHTML = ''
                        
                        document.getElementById('chat-username').innerText = socket_msg.username

                        document.getElementById('chat-username').setAttribute('id_user', socket_msg.id_user)
                        $('#username-status').innerHTML = socket_msg.user_estado


                        setTimeout(barra, 1000);
                    }
                })

            } else {
                document.getElementById('chats-list').innerHTML = ''
                var html = `Não há usuário com esse username...`
                chats_list.prepend(html)

                setTimeout( () => {
                    var msg = {
                        action : "connect",
                        id: my_id,
                        secret_key: secret_key
                    }
                    msg = JSON.stringify(JSON.stringify(msg))
                    socket.send(msg)
    
                    document.getElementById('chats-list').innerHTML = ''

                },1000)

            }
        break;
    }
}






function createCardOther(id_message, message, hora) {
    var card_message_other = `
      <div id= "${id_message}" class="card-message-other">
        <div class="message-other-text">
            ${message}
        </div>
        <div class="message-other-date">
            ${hora}
        </div>
      </div>`
  
      return card_message_other;
  }
  
function createCardMe(id_message, message, hora, estado = 0) {

    if(estado != "0") {
        var card_message_me = `
        <div id="${id_message}" class="card-message-me">
            <div class="message-me-text">
                ${message}
            </div>
            <div class="message-me-date">
                ${hora}
                    
                <a><i class="fas fa-check-circle"></i></a>
                <!--<a><i class="fas fa-check-circle"></i></a> -->
            </div>
        </div>`
    
        return card_message_me;
    } else {
        var card_message_me = `
        <div id="${id_message}" class="card-message-me">
            <div class="message-me-text">
                ${message}
            </div>
            <div class="message-me-date">
                ${hora}
                <a class="read"><i class="far fa-check-circle"></i></a>

            </div>
        </div>`
    
        return card_message_me;
    }
}

function createChatCard(img_url, username, hora, last_user, last_msg, nova_msg=0) {
    
    if(nova_msg != 0) {
        var chat_card = `
        <div username="${username}"id="chat${username}" class="chat-card">
            <div id="img-username" class="chat-img-circle">
                <img class="img-profile" src="${img_url}" alt="">
            </div>
            <div class="chat-info">
                <div class="chat-info-top">
                    <a id="username${username}"class="msg-username">${username}</a>
                    <a class="msg-date">${hora}</a>
                </div>
                <div class="chat-info-bot">
                    <a class="last-user">${last_user}:</a>
                    <a class="last-msg-new"> 
                        ${last_msg}
                    </a>
                </div>
            </div>
        </div>`
    } else {
        var chat_card = `
        <div username="${username}"id="chat${username}" class="chat-card">
            <div id="img-username" class="chat-img-circle">
                <img class="img-profile" src="${img_url}" alt="">
            </div>
            <div class="chat-info">
                <div class="chat-info-top">
                    <a id="username${username}"class="msg-username">${username}</a>
                    <a class="msg-date">${hora}</a>
                </div>
                <div class="chat-info-bot">
                    <a class="last-user">${last_user}:</a>
                    <a class="last-msg"> 
                        ${last_msg}
                    </a>
                </div>
            </div>
        </div>`
    }


    return chat_card;
}



function barra() {
    var tx = document.getElementsByTagName('textarea')
  
    if(tx != null) {
      for (var i = 0; i < tx.length; i++) {
        tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight) + 'px;overflow-y:hidden;')
        tx[i].addEventListener("input", OnInput, false)
      }
  
      function OnInput() {
        this.style.height = 'auto'
        this.style.height = (this.scrollHeight) + 'px'
      }
  
    }
  
    //fazer isso só se não tiver mensagem nova
    var myDiv = document.getElementById("content-chat");
    myDiv.scrollTop = myDiv.scrollHeight;
  
  }

function retornaDataAtual(){
    var dNow = new Date();
    var localdate = dNow.getDate() + '-' + (dNow.getMonth()+1) + '-' + dNow.getFullYear() + ' ' + dNow.getHours() + ':' + dNow.getMinutes()+ ':' + dNow.getSeconds();
    return localdate;
}

function createCardSearch(img_url, username, ultimo_acesso, descricao) {
    
    var chat_card = `
    <div id="result-card" username="${username}"id="chat${username}" class="chat-card">
        <div id="img-username" class="chat-img-circle">
            <img class="img-profile" src="${img_url}" alt="">
        </div>
        <div class="chat-info">
            <div class="chat-info-top">
                <a id="username${username}"class="msg-username">${username}</a>
                <a class="msg-date">Visto por ultimo ${ultimo_acesso}<a onclick = "$('#result-card').remove()"id="close-result"><i class="far fa-times-circle"></a></i></a>
            </div>
            <div class="chat-info-bot">
                <a class="last-user">Status:</a>
                <a class="last-msg"> 
                    ${descricao}
                </a>
            </div>
        </div>

    </div>`


    return chat_card;
}


  
var btn_search = document.getElementById('buscar')

if(btn_search != null) {
    btn_search.onclick = () => {   
        var search_text = document.getElementById('search-text').value
        if(search_text != '') {
            var json = {
                action: "search_user",
                from: my_id,
                user: search_text 
            }

            json = JSON.stringify(JSON.stringify(json))
            socket.send(json)
            document.getElementById('search-text').value = ''
        }
    }
}

function searchChatByUsername(username) {
    var chat = document.getElementById(`chat${username}`)

    if(chat != null) {
        return true;
    } else {
        return false;
    }
}

function encodeHTML(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
}