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

    $("#olho-chave").click( function(e) {
        e.preventDefault();

        if($("#olho-chave").hasClass('fas fa-eye')) {
            $("#olho-chave").removeClass('fas fa-eye')
            $("#olho-chave").addClass('fa fa-eye-slash')

            $('#chave_privada').prop('type', 'password')

        } else {
            $("#olho-chave").removeClass('fa fa-eye-slash')
            $("#olho-chave").addClass('fas fa-eye')

            $('#chave_privada').prop('type', 'text')

        }
    })

    
    $("#form").on('submit', function(e) {
        e.preventDefault();

        var username = document.getElementById('username').value 
        var senha = document.getElementById('senha').value 
        var chave = document.getElementById('chave_privada').value 

        axios.post('http://flowgram.test/api/usuarios/', {
                "acao": "entrar",
                "username": username,
                "senha": senha,

          })
          .then(function (response) {
            var json = response.data
    
            if(json.login == true) {
                localStorage.setItem('chave', chave)
                localStorage.setItem('id', json.id)

                window.location.href="http://flowgram.test/"
            } 
          })

        
    })
})

