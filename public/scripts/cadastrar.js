$(document).ready(function() {


    $("#olho-senha").click( function(e) {
        e.preventDefault();

        if($("#olho-senha").hasClass('fas fa-eye')) {
            $("#olho-senha").removeClass('fas fa-eye')
            $("#olho-senha").addClass('fa fa-eye-slash')
            
            $('#senha').prop('type', 'password')

        } else {
            $("#olho-senha").removeClass('fa fa-eye-slash')
            $("#olho-senha").addClass('fas fa-eye')

            $('#senha').prop('type', 'text')
            

        }
    })


    $("#form").on('submit', function(e) {
        e.preventDefault();

        var nome = document.getElementById('nome').value        
        var username = document.getElementById('username').value
        var senha = document.getElementById('senha').value 

        axios.post('http://flowgram.test/api/usuarios/', {
                "acao": "cadastrar",
                "username": username,
                "nome": nome,
                "senha": senha,

          })
          .then(function (response) {
            var json = response.data
            if(json.singup == true) {
                var chave = json.chave_privada
                localStorage.setItem('chave', chave)
                localStorage.setItem('id', json.id)
                window.location.href="http://flowgram.test/"
            } 
          })

    })

})
