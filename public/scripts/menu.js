var menu = document.getElementById('menu')
var menu_drop = document.getElementById('dropdown-menu')

var listener = () => {
    menu_drop.style.display = 'none'
    window.removeEventListener('click', listener , false)

}

menu.onclick = function(){
    if(menu_drop.style.display == 'block') {
        menu_drop.style.display = 'none'
    } else {
        menu_drop.style.display = 'block'
        setTimeout( () => {
                window.addEventListener('click', listener , false)
            }
        , 100)
    }
} 

var log_out = document.getElementById('log-out')

log_out.onclick = function() {
    console.log('oi')

    axios.post('/api/usuarios/', {
            "acao": "sair"
        })
        .then(function (response) {
        var msg = {
            action:'disconnect',
            id: my_id
        }
        msg = JSON.stringify(JSON.stringify(msg))
        socket.send(msg)
        socket.close()
        window.location.href="login.php"

        })

}


var btns_menu = document.getElementsByClassName('btn-menu')

for(let i in btns_menu) {
    btns_menu[i].onclick = function(){
        document.getElementById('content-chat').innerHTML = ''
        document.getElementById('chat-username').innerHTML = ''
        document.getElementById('username-status').innerHTML = ''

        var funcao = btns_menu[i].getAttribute('data-toggle').toString()

        switch(funcao) {
            case 'security':
                var html = createSecurity();
                document.getElementById('content-chat').innerHTML = html
            break;
            case 'dados':
                var html = createDadosConta();
                document.getElementById('content-chat').innerHTML = html
            break;
            case 'estatisticas':
                var html = createEstatistica();
                document.getElementById('content-chat').innerHTML = html
                // fazer uma req axas pra buscar as estatisticas
            break;
            case 'perfil':
                var html = createPerfil()
                document.getElementById('content-chat').innerHTML = html
            break;
        }
    }
}

function createSecurity() {
    var html_security = ` 
        <div id="security" class="security">
            <h2>Segurança</h2>
            <div class="article">
                <div><i class="fas fa-database f-100"></i></div>
                <div>
                    Para iniciar uma conversa com outro usuário. 
                    Cada usuário tem um par de chaves, 
                    onde uma é pública e está salva em nossos Banco de Dados para qualquer um enviar e receber mensagens de todos.
                    A outra é uma chave privada, e não está salva em nosso Banco de Dados, por isso precisamos de recebê-la todas 
                    as vezes que cada usuário faz log in para descriptografarmos todas as mensagens dos chats que ele participa. Logo 
                    após descriptografarmos a mensagem
                </div>
            </div>
            <div class="article">
                <div><i class="fas fa-lock f-100"></i></div>
                <div>
                    Ou seja, as mensagens só podem ser descriptografadas por participantes da conversa,
                    por esse motivo não é possível um usuário ver mensagens anteriores de um grupo ao entrar,
                    pois ele não recebeu as mensagens anteriores.        
                </div>
            </div>
        </div>`

    return html_security
}

function createDadosConta() {
    var html_dados = `            
    <div id="dados-conta" class="dados-conta">
        <h2>Solicitar dados da conta</h2>
        <h3><i class="fas fa-file-alt f-100"></i></h3>
        <div class="article">
            <div><a href="relatorio.php"><i class="fas fa-file-alt "></i>Gerar relatório</a></div>
            <div class="text-article">
                Crie um relatório com os dados de sua conta do Flowgram, 
                o qual contém todos os dados que estão guardados em nossos servidores sobre o seu usuário, exceto as mensagens.
                Caso queria exportar uma conversa, vá até ela e clique em exportar conversa (no canto superior direito).
                O seu relatório será gerado em poucos minutos. 
            </div>
        </div>
    </div>`

    return html_dados
}

function createEstatistica() {
    var html_estatistica = `
        <div id="estatistica" class="dados-conta">
            <h2>Estatísticas</h2>
            <h3><i class="fas fa-chart-pie f-100"></i></h3>
            <div id="estatisticas"class="article">
  
            </div>
        </div>
    `
    return html_estatistica
}

function criarCardEstatistica(img_url, username, qtd_msg) {
    var html_card = `<!-- Para cada conversar mostrar isso -->
    <div  class="chat-card">
        <div id="img-username" class="chat-img-circle">
            <img class="img-profile" src="${img_url}" alt="">
        </div>
        <div class="chat-info">
            <div class="chat-info-top">
                <a class="msg-username">${username}</a>
            </div>
            <div class="chat-info-bot">
                <a class="last-user">Mensagens</a>
                <a class="last-msg-new"> 
                    ${qtd_msg}
                </a>
            </div>
        </div>
    </div>
    <!-- FIM -->`

    return html_card
}
