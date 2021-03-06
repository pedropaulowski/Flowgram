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
                textarea.style.display = 'none'
            break;
            case 'dados':
                var html = createDadosConta();
                document.getElementById('content-chat').innerHTML = html
                textarea.style.display = 'none'

            break;
            case 'estatisticas':
                var html = createEstatistica();
                document.getElementById('content-chat').innerHTML = html
                console.log(html);
                textarea.style.display = 'none'
                axios({
                    method: 'post',
                    url: '/api/total'
                })
                .then(function (response) {
                    var json = response.data

                    for(var i in json) {
                        var html = criarCardEstatistica(json[i].img_url, json[i].usuario, json[i].total);
                        $("#estatisticas").append(html);
                    }

                });

            break;
            case 'perfil':
                textarea.style.display = 'none'
                var perfil;

                axios.get('/api/usuarios/', {
                    params: {
                        id_usuario: my_id,
                    }
                })
                .then(function (response) {
                    var json = response.data
                    if(json.nome != null && json.nome != undefined) {
                        perfil = json;             
                        var html = createPerfil(perfil.img_url, perfil.username, perfil.nome, perfil.descricao)
                        document.getElementById('content-chat').innerHTML = html
                    } 
                })

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
            <div><a href="api/relatorio" download="relatorio do flowgram" ><i class="fas fa-file-alt "></i>Gerar relatório</a></div>
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
        </div>`
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

function createPerfil(img_url, username, name, description) {
    var html = `                
    <div class="profile">
        <form id="form-edit" method="POST" enctype="multipart/form-data">
            <div  class="profile-img-circle">
                <img id="open-img-input"class="img-my-profile" src="${img_url}" alt="">
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
                <input autocomplete="off" name="name" type="text" class="input-profile"  value="${name}"id="name-text"/>
            </div>
            <a>Recado:</a>
            <div class="edit-descripton">
                <textarea autocomplete="off" name="description" id="description-profile" >${description}</textarea>
            </div>
            
            <button type="submit" id="editar"><i class="fas fa-save"></i></button>

        </form>
    </div>`;

    $(function(){
        $('#form-edit').on('submit', function(e) {
            e.preventDefault();

            var form = document.getElementById('form-edit')
            var formData = new FormData(form)

            $.ajax({
                type: 'POST',
                url:'/api/editprofile/',
                contentType:false,
                data: formData,
                processData:false,
                success:function(response) {
                    var json = JSON.parse(response)           
                    document.getElementById('content-chat').innerHTML = ''
                    console.log(json)
                    var html = createPerfil(json.img_url, json.username, json.nome, json.descricao)
                    document.getElementById('content-chat').innerHTML = html
                }
            })
        })
    })


    return html
}


function conversas() {
    if(document.getElementById('chats-list').style.display == 'none' || document.getElementById('chats-list').style.display == '')   
        document.getElementById('chats-list').style.display = 'block'
    else 
        document.getElementById('chats-list').style.display = 'none'

}




