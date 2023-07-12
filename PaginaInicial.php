
<?php
// Verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver autenticado
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Página Home</title>
    <script src="/siasb_html/flowSite/verificaSessao.js"></script>

    <style>

table {
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid black;
            cursor: pointer;
        }

        th {
            background-color: #ccc;
        }

        td {
            background-color: #fff;
        }

        td.selected-day {
            background-color: #eee;
        }

        .current-day {
            background-color: blue;
            color: #fff;
        }

        .notes-section {
            display: none;
            margin-top: 20px;
        }

        .notes-content {
            width: 100%;
            height: 100px;
        }

        .notes-submit {
            margin-top: 10px;
        }

        .saved-notes {
            margin-top: 20px;
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
            color: blue;
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
<body onload="exibirHorarioAtual()" onload="exibirDataAtual()" style="background: none;" >
    <div class="container">

    
        <header>
            <h1>Bem-vindo, <?php echo $_SESSION['username']; ?>!</h1>
            <p>Aqui está o conteúdo restrito do painel de controle.</p>

            <!-- Inclua o conteúdo adicional do painel de controle aqui -->

            <a href="../flowSite/encerrarSessao.php">Sair</a> <!-- Adicione o link de logout para encerrar a sessão -->
             </header>


        <div class="welcome-message">
            <h2>Olá, <?php echo $_SESSION['username']; ?> !</h2>
            <p>Seja bem-vindo ao sistema de chamados. Esperamos que tenha um ótimo diaaaa.</p>
        </div>

        <div class="info-panel">
            <div>
                <h3>Data Atual</h3>
                <p id="dataAtual">[Data Atual]</p>
            </div>
            <div>
                <h3>Horário</h3>
                <p id="horario-atual">[Horário Atual]</p>
            </div>
        </div>

        <div class="calendar">
            
            <h3>Calendário</h3>
            <!-- Inclua aqui o código do calendário desejado -->
        </div>



        <!-- REALIZAÇÃO DO CALENDÁRIO -->
        <h1>Calendário com Anotações</h1>
    <label for="month-select">Escolha o mês:</label>
    <select id="month-select" onchange="changeMonth()">
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

    <div id="notes-section" class="notes-section">
        <h2>Anotações</h2>
        <form id="notes-form">
            <input type="hidden" id="selected-date" name="selected-date">
            <textarea id="notes-content" class="notes-content" name="notes-content" placeholder="Faça suas anotações aqui"></textarea>
            <button type="submit" class="notes-submit">Salvar</button>
        </form>
        <div id="saved-notes" class="saved-notes">
            <h3>Todas as Anotações</h3>
            <ul id="notes-list"></ul>
        </div>
    </div>

    <script>
        // Função para obter o calendário de um mês específico
        function getCalendar(month, year) {
            // Obter data atual
            var currentDate = new Date();

            // Configurar data para o primeiro dia do mês específico
            var date = new Date(year, month, 1);

            // Obter o número do dia da semana do primeiro dia
            var firstDay = date.getDay();

            // Obter o último dia do mês
            var lastDay = new Date(year, month + 1, 0).getDate();

            var calendarBody = document.getElementById('calendar-body');

            // Limpar conteúdo do corpo do calendário
            calendarBody.innerHTML = '';

            // Adicionar linhas para cada semana do mês
            var row = document.createElement('tr');
            var day = 1;

            for (var i = 0; i < 42; i++) {
                var cell = document.createElement('td');

                if (i >= firstDay && day <= lastDay) {
                    cell.innerText = day;

                    // Adicionar classe 'current-day' ao dia atual
                    if (month === currentDate.getMonth() && year === currentDate.getFullYear() && day === currentDate.getDate()) {
                        cell.classList.add('current-day');
                    }

                    // Adicionar evento de clique para exibir campo de anotações
                    cell.addEventListener('click', function() {
                        var selectedDate = new Date(year, month, parseInt(this.innerText));

                        // Atualizar classe das células para remover a seleção anterior
                        var cells = document.getElementsByTagName('td');
                        for (var j = 0; j < cells.length; j++) {
                            cells[j].classList.remove('selected-day');
                        }

                        // Adicionar classe 'selected-day' à célula selecionada
                        this.classList.add('selected-day');

                        // Preencher data selecionada no campo escondido
                        document.getElementById('selected-date').value = selectedDate.toISOString().split('T')[0];

                        // Exibir campo de anotações
                        showNotesForm();
                    });
                    day++;
                }

                row.appendChild(cell);

                if (i % 7 === 6) {
                    calendarBody.appendChild(row);
                    row = document.createElement('tr');
                }
            }

            // Adicionar a última linha, se necessário
            if (row.childNodes.length > 0) {
                calendarBody.appendChild(row);
            }
        }

        // Função para atualizar o mês do calendário ao selecionar uma opção da caixa de seleção
        function changeMonth() {
            var monthSelect = document.getElementById('month-select');
            var selectedMonth = parseInt(monthSelect.value);
            var currentYear = new Date().getFullYear();

            getCalendar(selectedMonth, currentYear);
        }

        // Função para exibir o formulário de anotações
        function showNotesForm() {
            document.getElementById('notes-section').style.display = 'block';
        }

        // Função para lidar com o envio do formulário de anotações
        document.getElementById('notes-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var selectedDate = document.getElementById('selected-date').value;
            var notesContent = document.getElementById('notes-content').value;

            // Aqui você pode adicionar a lógica para enviar os dados para o backend e armazenar no banco de dados

            // Após salvar as anotações, você pode adicionar o conteúdo ao elemento <ul> das anotações salvas
            var notesList = document.getElementById('notes-list');
            var listItem = document.createElement('li');
            listItem.innerText = selectedDate + ': ' + notesContent;
            notesList.appendChild(listItem);

            // Limpar o formulário após o envio
            document.getElementById('notes-content').value = '';

            // Remover a classe 'selected-day' da célula selecionada
            var selectedCell = document.getElementsByClassName('selected-day')[0];
            selectedCell.classList.remove('selected-day');
        });

        // Chamar a função para gerar o calendário do mês atual
        var currentMonth = new Date().getMonth();
        var currentYear = new Date().getFullYear();
        document.getElementById('month-select').value = currentMonth.toString();
        getCalendar(currentMonth, currentYear);
    </script>



        <footer>
            &copy; 2023 Sistema de Chamados. Todos os direitos reservados.
        </footer>
    </div>
</body>
</html>
