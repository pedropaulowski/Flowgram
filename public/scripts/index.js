const socket = new WebSocket("ws://192.168.1.14:8080")

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

function createCardMe(id_message, message, hora) {

  var card_message_me = `
    <div id="${id_message}" class="card-message-me">
        <div class="message-me-text">
          ${message}
        </div>
        <div class="message-me-date">
            ${hora}
              
            <a class="read"><i class="far fa-check-circle"></i></a>

            <!--<a><i class="fas fa-check-circle"></i></a> -->
        </div>
    </div>`

    return card_message_me;

}
/*
var chave = localStorage.getItem('chave')

var msg = {
action: "message",
to:"c6d445928ac8c8b0ca30abf81a5daa34",
from: localStorage.getItem('id'),
msg: {
    type:"text",
    msg: "Primeira Mensagem"
},
privacidade: {
  "tipo":"individual",
  
},
estado: 0,
chave_privada: chave
}

msg = JSON.stringify(JSON.stringify(msg))

socket.send(msg)*/