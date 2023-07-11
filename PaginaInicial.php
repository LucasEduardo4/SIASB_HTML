
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

        <footer>
            &copy; 2023 Sistema de Chamados. Todos os direitos reservados.
        </footer>
    </div>
</body>
</html>
