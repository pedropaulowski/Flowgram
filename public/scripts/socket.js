const socket = new WebSocket("ws://192.168.1.14:8080")
const my_id = localStorage.getItem('id')
const secret_key = localStorage.getItem('chave')


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
            var html = createCardMe(Math.floor(Math.random() * 1000), msg_text, hora, 0)
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
                console.log()
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
                console.log(document.getElementById(id_div).getAttribute('username'), document.getElementById('chat-username').innerText);
                    
                    var msg = {
                        action: 'Open chat',
                        requester: my_id,
                        other: socket_msg.id_user,
                        secret_key: secret_key,
                        pagina: 0
                    }

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

function createChatCard(img_url, username, hora, last_user, last_msg) {
    
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