<?php
// Verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver autenticado
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="/siasb_html/flowSite/verificaSessao.js"></script>

    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
            max-width: 100%;
            background: none;
        }

        .div-superior {
            width: 400px;
            background-color: #ffffff;
            padding: 20px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .logo {
            position: absolute;
            width: 40px;
            height: 40px;
            left: 78px;
            margin: 0 auto;
            background-color: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
            top: 35px;
        }

        .logo img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 6px;
        }

        .div-inferior {
            width: 450px;
            background-color: #ffffff;
            padding: 40px;
            padding-top: 100px;
            z-index: 1;
            margin-bottom: 50px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .div-azul {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            position: absolute;
            background-color: blue;
            padding-top: 50px;
            color: #ffffff;
            padding: 15px;
            border-radius: 5px;
            z-index: 2;
            width: 420px;
            top: 285px;
            text-align: center;
            font-size: 15px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .field input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .submit-button {
            padding-top: 10px;
            padding-bottom: 10px;
            margin-top: 20px;
            width: 100%;
        }
    </style>
</head>
<body style="background: none;">

    <div class="container">
        <div style="border-radius: 5px;" class="div-superior">
            <div style="border-radius: 10px;" class="logo">
                <img src="C:\Users\lucas\Downloads\pngtree-vector-notification-icon-png-image_855007.png">
            </div>
            <h2 style="color: rgb(0, 0, 181);">GERENCIAMENTO DO PERFIL</h2>
        </div>
    </div>

    <div class="container">
        <div style="border-radius: 5px; " class="div-inferior">
            <div style="border: 2px;" class="div-azul">INFORMAÇÕES DO USUÁRIO</div>
            <!-- Conteúdo adicional da div inferior -->
            <div class="field">
                <label for="nome">Nome:</label>
                <p id="nome">John</p>
            </div>

            <div class="field">
                <label for="sobrenome">Sobrenome:</label>
                <p id="sobrenome">Doe</p>
            </div>

            <div class="field">
                <label for="usuario">Usuário:</label>
                <p id="usuario">johndoe</p>
            </div>

            <div class="field">
                <label for="senha">Senha:</label>
                <p id="senha">********</p>
            </div>

            <button class="submit-button" type="submit" onclick="editarInformacoes()">EDITAR</button>
        </div>
    </div>

    <script>
        function editarInformacoes() {
            // Obtém os valores atuais dos campos de parágrafo
            var nomeParagrafo = document.getElementById("nome");
            var sobrenomeParagrafo = document.getElementById("sobrenome");
            var usuarioParagrafo = document.getElementById("usuario");
            var senhaParagrafo = document.getElementById("senha");

            // Obtém os valores atuais dos campos de parágrafo
            var nomeValor = nomeParagrafo.textContent;
            var sobrenomeValor = sobrenomeParagrafo.textContent;
            var usuarioValor = usuarioParagrafo.textContent;
            var senhaValor = senhaParagrafo.textContent;

            // Substitui os campos de parágrafo pelos campos de entrada de texto
            nomeParagrafo.innerHTML = '<input type="text" id="nomeInput" value="' + nomeValor + '">';
            sobrenomeParagrafo.innerHTML = '<input type="text" id="sobrenomeInput" value="' + sobrenomeValor + '">';
            usuarioParagrafo.innerHTML = '<input type="text" id="usuarioInput" value="' + usuarioValor + '">';
            senhaParagrafo.innerHTML = '<input type="password" id="senhaInput" value="' + senhaValor + '">';

            // Altera o texto do botão para "SALVAR" e define o evento de clique
            var editarButton = document.getElementsByClassName("submit-button")[0];
            editarButton.textContent = "SALVAR";
            editarButton.setAttribute("onclick", "salvarInformacoes()");
        }

        function salvarInformacoes() {
            // Obtém os valores dos campos de entrada de texto
            var nomeInput = document.getElementById("nomeInput");
            var sobrenomeInput = document.getElementById("sobrenomeInput");
            var usuarioInput = document.getElementById("usuarioInput");
            var senhaInput = document.getElementById("senhaInput");

            // Obtém os valores dos campos de entrada
            var nomeValor = nomeInput.value;
            var sobrenomeValor = sobrenomeInput.value;
            var usuarioValor = usuarioInput.value;
            var senhaValor = senhaInput.value;

            // Substitui os campos de entrada de texto pelos parágrafos atualizados
            nomeInput.parentNode.innerHTML = nomeValor;
            sobrenomeInput.parentNode.innerHTML = sobrenomeValor;
            usuarioInput.parentNode.innerHTML = usuarioValor;
            senhaInput.parentNode.innerHTML = senhaValor;

            // Altera o texto do botão de volta para "EDITAR" e define o evento de clique
            var editarButton = document.getElementsByClassName("submit-button")[0];
            editarButton.textContent = "EDITAR";
            editarButton.setAttribute("onclick", "editarInformacoes()");
        }
    </script>

</body>
</html>