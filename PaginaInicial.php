<?php
// Verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver autenticado
    exit();
}
?>


<!-- PHP PARA REALIZAR A INSERÇÃO DAS ANOTAÇÕES NO BANCO DE DADOS -->
<?php
// Faça a consulta para obter o ID do usuário
// Realizar a conexão com o banco de dados (substitua os valores conforme suas configurações)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siasb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Certifique-se de que a variável $usuario_ esteja definida com o nome do usuário atual
$usuario_ = $_SESSION['username'];

// Faça a consulta para obter o ID do usuário
$sql_id_usuario = "SELECT IDUsuario FROM tbusuario WHERE nome = '$usuario_'";

$nome_user = "SELECT nomeCompleto FROM tbusuario WHERE nome = '$usuario_'";


$result_id_usuario = mysqli_query($conn, $sql_id_usuario);

if ($result_id_usuario) {
    $row_id_usuario = mysqli_fetch_assoc($result_id_usuario);
    $id_usuario = $row_id_usuario['IDUsuario'];

    // Faça a consulta para recuperar as anotações do usuário com base no ID do usuário
    $sql_anotacoes = "SELECT mensagem, dia, IDAgenda FROM tbagenda WHERE IDUsuario = '$id_usuario'";

    $result_anotacoes = mysqli_query($conn, $sql_anotacoes);

    // Crie um array para armazenar as anotações
    $anotacoes_usuario = array();

    while ($row_anotacoes = mysqli_fetch_assoc($result_anotacoes)) {
        $anotacoes_usuario[] = $row_anotacoes;
    }
} else {
    // Trate o erro, se necessário
    echo "Erro ao obter ID do usuário: " . mysqli_error($conn);
}
?>

<?php //terminar aqui.

$conn = new mysqli('localhost', 'root', '', 'siasb');

$sql = "SELECT nomeCompleto 
        FROM TBUSUARIO u
        JOIN TBPessoa p ON p.IDPessoa = u.IDUsuario
        WHERE nome = ?";

// Preparar o statement
$stmt = $conn->prepare($sql);

// Vincular o parâmetro com o valor
$username = $_SESSION["username"];
$stmt->bind_param("s", $username);

// Executar a consulta
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nomeUser = $row["nomeCompleto"];
    $_SESSION['nomeUsuario'] = $nomeUser;
} else {
    echo "Nenhum resultado encontrado.";
}

// Fechar o statement
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Página Home</title>
    <script src="/siasb_html/flowSite/verificaSessao.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Definir uma fonte mais elegante */
        body {
            font-family: "Helvetica Neue", Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            z-index: 10;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #333;
            text-align: center;
        }

        .anotacoes-list {
            list-style: none;
            padding: 0;
        }

        .anotacao-item {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .data {
            margin: 0;
            font-weight: bold;
            color: #555;
        }

        .conteudo {
            margin: 10px 0;
        }

        .botoes {
            display: flex;
            justify-content: flex-end;
        }

        .botoes button {
            margin-left: 10px;
            padding: 8px 15px;
            /* border: none; */
            border-radius: 3px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .botoes button:hover {
            background-color: #0056b3;
        }






        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        .calendar-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        .dropdown-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .dropdown-container select {
            padding: 8px;
            margin-left: 10px;
            padding-right: 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            cursor: pointer;
        }

        .current-day {
            background-color: #e6f2ff;
            font-weight: bold;
        }

        .selected-day,
        td:hover {
            background-color: #f5f5f5;
        }

        .notes-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            width: 400px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            z-index: 999;
        }

        .notes-popup h2 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .notes-content {
            width: 100%;
            height: 100px;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .notes-submit {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            background-color: blue;
            color: #fff;
            cursor: pointer;
        }

        .notes-list {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            list-style-type: none;
        }

        .notes-list li {
            margin-bottom: 10px;
        }

        .note-date {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .conteudo_salvo {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 30px;
        }






        /* Estilos gerais */
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        /* Estilos do contêiner principal */
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Estilos do cabeçalho */
        header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            color: #00a383;
            margin-top: 0;
        }

        /* Estilos da mensagem de boas-vindas */
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }

        h2 {
            color: #333;
        }

        /* Estilos do painel de informações */
        .info-panel {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-panel div {
            flex-basis: 30%;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .info-panel h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .info-panel p {
            margin: 0;
            color: #666;
            font-size: 16px;
        }

        /* Estilos do calendário */
        .calendar {
            text-align: center;
            margin-bottom: 30px;
        }

        /* Estilos do rodapé */
        footer {
            text-align: center;
            color: #888;
        }
    </style>

    <style>
        .editar-anotacao {
            display: none;
        }

        .close-popup {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }


        .selected-date {
            background-color: green;
            color: green;
        }
    </style>


    <script>
        function exibirHorarioAtual() {
            var horasAtual = new Date();
            var hora = horasAtual.getHours();
            var minutos = horasAtual.getMinutes();
            var segundos = horasAtual.getSeconds();

            // Formatação para garantir que sempre tenhamos 2 dígitos
            if (hora < 10) {
                hora = "0" + hora;
            }
            if (minutos < 10) {
                minutos = "0" + minutos;
            }
            if (segundos < 10) {
                segundos = "0" + segundos;
            }

            // Exibir o horário atual na página
            document.getElementById("horario-atual").innerHTML = hora + ":" + minutos + ":" + segundos;

            // Atualizar o horário a cada segundo
            setTimeout(exibirHorarioAtual, 1000);
        }

        function exibirDataAtual() {
            var dataAtual = new Date();
            var dia = dataAtual.getDate();
            var mes = dataAtual.getMonth() + 1; // Os meses são indexados de 0 a 11
            var ano = dataAtual.getFullYear();

            // Formatação para exibição do formato desejado (opcional)
            if (dia < 10) {
                dia = '0' + dia;
            }
            if (mes < 10) {
                mes = '0' + mes;
            }

            var dataFormatada = dia + '/' + mes + '/' + ano;

            // Exibe a data atual na página
            document.getElementById('dataAtual').textContent = dataFormatada;
        }

    </script>


</head>

<body onload="exibirHorarioAtual()" onload="exibirDataAtual()" style="background: none;">
    <div class="container">



        <header>
            <h1>Bem-vindo,
                <?php echo $_SESSION['nomeUsuario']; ?>!
            </h1>
            <p>Seja bem-vindo ao sistema de chamados. Esperamos que tenha um ótimo diaaaa.</p>

            <!-- Inclua o conteúdo adicional do painel de controle aqui -->

            <!-- <a href="../flowSite/encerrarSessao.php">Sair</a> -->
        </header>


        <div class="welcome-message">
            <!-- <h2>Olá,
                <?php echo $_SESSION['nomeUsuario']; ?> !
            </h2> -->
        </div>

        <!-- <div class="info-panel">

            <div>
                <h3>Horário</h3>
                <p id="horario-atual">[Horário Atual]</p>
            </div>
        </div> -->

        <div class="calendar-container">
            <h1>Calendário</h1>

            <div class="dropdown-container">
                <select id="month-select" onchange="getCalendar()">
                    <option value="0">Janeiro</option>
                    <option value="1">Fevereiro</option>
                    <option value="2">Março</option>
                    <option value="3">Abril</option>
                    <option value="4">Maio</option>
                    <option value="5">Junho</option>
                    <option value="6">Julho</option>
                    <option value="7">Agosto</option>
                    <option value="8">Setembro</option>
                    <option value="9">Outubro</option>
                    <option value="10">Novembro</option>
                    <option value="11">Dezembro</option>
                </select>

                <select id="year-select" onchange="getCalendar()"></select>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Domingo</th>
                        <th>Segunda-feira</th>
                        <th>Terça-feira</th>
                        <th>Quarta-feira</th>
                        <th>Quinta-feira</th>
                        <th>Sexta-feira</th>
                        <th>Sábado</th>

                    </tr>
                </thead>
                <tbody id="calendar-body"></tbody>
            </table>

            <div id="notes-popup" class="notes-popup">
                <button id="close-popup" class="close-popup">x</button>
                <h2>Anotações</h2>
                <form id="notes-form">
                    <input type="hidden" id="selected-date" name="selected-date">
                    <textarea id="notes-content" class="notes-content" name="notes-content"
                        placeholder="Faça suas anotações aqui"></textarea>
                    <button type="submit" class="notes-submit">Salvar</button>
                </form>
            </div>

            <script>
                const closePopupButton = document.getElementById('close-popup');
                const notesPopup = document.getElementById('notes-popup');

                closePopupButton.addEventListener('click', () => {
                    notesPopup.style.display = 'none';
                });

            </script>



            <!-- IREI COLOCAR O CÓDIGO PARA ALTERAR A COR DO CALENDÁRIO AQUI -->

            <script>
                function getCalendar() {
                    // Implemente a lógica para atualizar a tabela de calendário aqui

                    // Suponhamos que você queira destacar a data 5 de agosto
                    const targetDate = new Date(2023, 7, 5); // Note que os meses são indexados de 0 a 11

                    // Encontre o elemento de data correspondente na tabela
                    const calendarCells = document.querySelectorAll("#calendar-body td");
                    calendarCells.forEach(cell => {
                        const cellDate = new Date(cell.getAttribute("data-date"));
                        if (cellDate.toDateString() === targetDate.toDateString()) {
                            cell.classList.add("selected-date");
                        } else {
                            cell.classList.remove("selected-date");
                        }
                    });
                }

                // Chame a função getCalendar() para iniciar o calendário
                getCalendar();
            </script>




            <!-- Adicione este código PHP para exibir as anotações -->

            <div class="container">
                <h3>Minhas Anotações:</h3>
                <ul class="anotacoes-list">
                    <?php foreach ($anotacoes_usuario as $anotacao) { ?>
                        <li class="anotacao-item">
                            <p class="data">Data:
                                <?php echo $anotacao['dia']; ?>
                            </p>
                            <p class="conteudo" contenteditable="true" data-id="<?php echo $anotacao['IDAgenda']; ?>">
                                <?php echo $anotacao['mensagem']; ?>
                            </p>
                            <div class="botoes">
                                <button class="excluir" data-id="<?php echo $anotacao['IDAgenda']; ?>">Excluir</button>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>


            <!-- REALIZANDO OS PROCEDIMENTOS DE EDITAR AS ANOTAÇÕES -->

            <script>
                function handleExcluirClick(event) {
                    var button = event.target;
                    var anotacaoId = button.getAttribute("data-id");

                    // Fazer a requisição AJAX usando JavaScript puro (sem jQuery)
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "excluir_anotacao.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // Remover a anotação da lista após a exclusão no banco de dados
                                var listItem = button.closest(".anotacao-item");
                                listItem.remove();
                            } else {
                                console.error("Erro ao excluir anotação:", xhr.responseText);
                            }
                        }
                    };
                    xhr.send("id=" + encodeURIComponent(anotacaoId));
                }

                function handleContentEditableBlur(event) {
                    var pElement = event.target;
                    var anotacaoId = pElement.getAttribute("data-id");
                    var novoConteudo = pElement.textContent;

                    // Fazer a requisição AJAX para atualizar a anotação no banco de dados
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "atualizar_anotacao.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status !== 200) {
                                console.error("Erro ao atualizar anotação:", xhr.responseText);
                            }
                        }
                    };

                    xhr.send("id=" + encodeURIComponent(anotacaoId) + "&novo_conteudo=" + encodeURIComponent(novoConteudo));
                }

                // Adicionar um ouvinte de eventos para os botões "Excluir"
                var excluirButtons = document.getElementsByClassName("excluir");
                for (var i = 0; i < excluirButtons.length; i++) {
                    excluirButtons[i].addEventListener("click", handleExcluirClick);
                }

                // Adicionar um ouvinte de eventos para os elementos com a classe "conteudo"
                var conteudoElements = document.getElementsByClassName("conteudo");
                for (var i = 0; i < conteudoElements.length; i++) {
                    conteudoElements[i].addEventListener("blur", handleContentEditableBlur);
                }
            </script>






            <script>
                function handleExcluirClick(event) {
                    var button = event.target;
                    var anotacaoId = button.getAttribute("data-id");
                    //   console.log(event); 


                    // Fazer a requisição AJAX usando jQuery
                    $.ajax({
                        url: "excluir_anotacao.php", // O arquivo PHP que irá processar a exclusão
                        type: "POST",
                        data: {
                            id: anotacaoId, // Enviar o ID da anotação a ser excluída
                            success: function (response) {
                                // Exibe a resposta em um elemento HTML com id "resposta"
                                document.getElementById("resposta").textContent = response;

                            },

                        },
                        success: function (response) {
                            // Remover a anotação da lista após a exclusão no banco de dados
                            var listItem = button.closest(".anotacao-item");
                            listItem.remove();


                        },
                        error: function (xhr, status, error) {
                            console.error(error); // Tratar erro, se necessário (opcional)
                        },
                    });
                }


                //FORMA REDUZIDA DE REALIZAR A EXCLUSÃO
                // $.ajax({
                //     url: "excluir_anotacao.php",
                //     type: "POST",
                //     data: {
                //         id: anotacaoId,
                //     },
                //     success: function (response) {
                //         document.getElementById("resposta").textContent = response;
                //         var listItem = button.closest(".anotacao-item");
                //         listItem.remove();
                //     },
                //     error: function (xhr, status, error) {
                //         console.error(error);
                //     },
                // });



                // Adicionar um ouvinte de eventos para os botões "Excluir"
                var excluirButtons = document.getElementsByClassName("excluir");
                for (var i = 0; i < excluirButtons.length; i++) {
                    excluirButtons[i].addEventListener("click", handleExcluirClick);
                }

            </script>





            <!-- Mais código HTML, se houver -->

            <!-- Código HTML existente -->



            <!-- AQUI IRA APARECER TODAS AS ANOTAÇÕES QUE IRAM SER SALVAS // class="note-date" -->
            <ul id="notes-list" class="notes-list"></ul>
        </div>

        <script>
            // Objeto para armazenar as anotações
            var notesData = {};

            // Obter elementos HTML
            var monthSelect = document.getElementById('month-select');
            var yearSelect = document.getElementById('year-select');

            // Preencher dropdown de anos com os últimos 20 anos
            var currentYear = new Date().getFullYear();
            for (var i = currentYear; i >= currentYear - 20; i--) {
                var option = document.createElement('option');
                option.value = i;
                option.text = i;
                yearSelect.appendChild(option);
            }

            // Função para obter o calendário do mês selecionado
            function getCalendar() {
                var selectedMonth = parseInt(monthSelect.value);
                var selectedYear = parseInt(yearSelect.value);
                var currentDate = new Date();
                var currentMonth = currentDate.getMonth();
                var calendarBody = document.getElementById('calendar-body');
                var firstDay = new Date(selectedYear, selectedMonth, 1).getDay();
                var lastDay = new Date(selectedYear, selectedMonth + 1, 0).getDate();

                calendarBody.innerHTML = '';

                var row = document.createElement('tr');

                // Preencher células vazias no início do mês
                for (var i = 0; i < firstDay; i++) {
                    var cell = document.createElement('td');
                    row.appendChild(cell);
                }

                // Preencher os dias do mês
                for (var day = 1; day <= lastDay; day++) {
                    var cell = document.createElement('td');
                    cell.innerText = day;

                    // Adicionar classe 'current-day' ao dia atual ================================================================================
                    if (selectedMonth === currentMonth && selectedYear === currentDate.getFullYear() && day === currentDate.getDate()) {
                        cell.classList.add('current-day');
                    }

                    // Adicionar evento de clique para exibir o popup de anotações
                    cell.addEventListener('click', function () {
                        var selectedDate = new Date(selectedYear, selectedMonth, parseInt(this.innerText));
                        var selectedDateString = formatDate(selectedDate);
                        document.getElementById('selected-date').value = selectedDateString;
                        showNotesPopup(selectedDateString);
                    });

                    // Verificar se a data possui anotações
                    var dateKey = formatDate(new Date(selectedYear, selectedMonth, day));
                    if (notesData[dateKey]) {
                        cell.classList.add('selected-day');
                    }

                    row.appendChild(cell);

                    if (row.children.length === 7) {
                        calendarBody.appendChild(row);
                        row = document.createElement('tr');
                    }
                }

                // Preencher células vazias no final do mês
                while (row.children.length < 7) {
                    var cell = document.createElement('td');
                    row.appendChild(cell);
                }

                calendarBody.appendChild(row);

                // Atualizar a exibição das anotações
                showAllNotes();
            }

            // Função para exibir o popup de anotações
            function showNotesPopup(selectedDate) {
                var popup = document.getElementById('notes-popup');
                popup.style.display = 'block';

                // Verificar se a data já possui anotações
                var notesContentInput = document.getElementById('notes-content');
                notesContentInput.value = notesData[selectedDate] || '';

                // Preencher o campo de data selecionada
                document.getElementById('selected-date').value = selectedDate;
            }

            // Função para fechar o popup de anotações
            function closeNotesPopup() {
                var popup = document.getElementById('notes-popup');
                popup.style.display = 'none';
            }

            // Função para lidar com o envio do formulário de anotações
            document.getElementById('notes-form').addEventListener('submit', function (event) {
                event.preventDefault();

                var selectedDate = document.getElementById('selected-date').value;
                var notesContent = document.getElementById('notes-content').value;

                // Salvar as anotações no objeto de notas
                notesData[selectedDate] = notesContent;

                // Fechar o popup de anotações
                closeNotesPopup();

                // Atualizar a exibição das anotações
                showAllNotes();
            });

            // Função para exibir todas as anotações
            function showAllNotes() {
                var notesList = document.getElementById('notes-list');
                notesList.innerHTML = '';

                for (var dateKey in notesData) {
                    if (notesData.hasOwnProperty(dateKey)) {
                        var note = document.createElement('li');
                        var noteDate = dateKey;
                        var noteContent = notesData[dateKey];
                        note.innerHTML = '<span class="note-date">' + noteDate + '</span>: ' + noteContent;
                        notesList.appendChild(note);
                    }
                }
            }

            // Função para formatar a data no formato brasileiro (dd/mm/yyyy)
            function formatDate(date) {
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();

                return (
                    year +
                    '-' +
                    (month < 10 ? '0' + month : month) +
                    '-' +
                    (day < 10 ? '0' + day : day)


                    // (day < 10 ? '0' + day : day) +
                    // '/' +
                    // (month < 10 ? '0' + month : month) +
                    // '/' +
                    // year
                );
            }

            var currentDate = new Date();
            var currentMonth = currentDate.getMonth();
            var currentYear = currentDate.getFullYear();
            monthSelect.value = currentMonth;
            yearSelect.value = currentYear;
            getCalendar();
            showAllNotes();

            // Chamar a função para gerar o calendário e exibir as anotações
            getCalendar();
            showAllNotes();





            // REALIZANDO TESTES PARA SALVAR AS ANOTAÇÕES

            // Função para enviar as anotações para o servidor


            document.getElementById('notes-form').addEventListener('submit', function (event) {
                event.preventDefault();

                var selectedDate = document.getElementById('selected-date').value;
                var notesContent = document.getElementById('notes-content').value;

                // Salvar as anotações no objeto de notas (opcionalmente, você pode fazer isso imediatamente após o usuário digitar a anotação)
                notesData[selectedDate] = notesContent;

                // Chamar a função para salvar as anotações no banco de dados
                saveNotesToDatabase(selectedDate, notesContent);

                // Fechar o popup de anotações
                closeNotesPopup();

                // Atualizar a exibição das anotações (opcionalmente, você pode fazer isso depois de receber a confirmação do servidor)
                showAllNotes();
            });




            function saveNotesToDatabase(selectedDate, notesContent) {
                // Fazer a requisição AJAX usando jQuery
                $.ajax({
                    url: 'salvar_anotacoes.php', // O arquivo PHP que vai salvar as anotações no banco de dados
                    type: 'POST',
                    data: {
                        date: selectedDate,
                        message: notesContent
                    },
                    success: function (response) {
                        console.log(response); // Imprimir resposta do servidor (opcional)
                    },
                    error: function (xhr, status, error) {
                        console.error(error); // Tratar erro, se necessário (opcional)
                    }
                });
            }



        </script>


        <footer>
            &copy; <p> 2023 Sistema de Chamados. Todos os direitos reservados. </p>
        </footer>
    </div>
</body>

</html>