<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    echo "javascript:window.location='../login.html';";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebars/bootstrap.min.css">
    <title>Main Configs</title>
    <!-- <script src="../flowSite/verificaSessao.js"></script> -->
    <script src="/siasb_html/flowSite/verificaSessao.js"></script>
    <script src="/siasb_html/flowSite/verificaPermissao.js"></script>

    <style>
        a {
            color: #00a383;
            text-decoration: none;
        }

        .caixas {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            /* min-height: 100vh; */
            background-color: #f0f0f0;
            flex-direction: row;
            flex-flow: space-around;
            align-items: stretch;
            flex-grow: 1;
            padding-top: 60px;
            padding-bottom: 60px;
        }

        @media (max-width: 768px) {
            .caixas {
                display: contents;
            }
        }

        .floating-box {
            background-color: white;
            padding-top: 100px;
            padding-bottom: 100px;
            padding-left: 30px;
            padding-right: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            margin: 10px;
            flex: 1;

        }

        .floating-box {
            background-color: white;
            padding-top: 100px;
            padding-bottom: 100px;
            padding-left: 30px;
            padding-right: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            margin: 10px;
            flex: 1;
            cursor: pointer;

            transition: transform 0.3s ease;
        }

        .floating-box:hover {
            transform: scale(1.1);
        }

        .flex-container {
            flex-flow: space-around;
        }

        img {
            align-content: center;
            pointer-events: none;


        }

        header {
            text-align: center;
            margin: 20px 0;
        }

        header h1 {
            font-size: 32px;
        }

        header p {
            font-size: 18px;
            color: #666;
        }

        hr {
            border: 1px solid #ddd;
            padding-top: 70px;
        }

        #center {
            display: flex;
            justify-content: space-between;
            margin: auto;
            max-width: 800px;
        }

        .panel {
            background-color: #ffffff;
            padding: 30px;
            padding-top: 50px;
            margin-top: 60px;
            border-radius: 8px;
            box-shadow: 0 30px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 40px;
            width: 45%;
        }

        .panel p {
            font-size: 18px;
            margin-bottom: 20px;

        }

        .panel a {
            display: block;
            background-color: #00a383;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-image 0.3s;
            /* Transição de 0.3 segundos no degradê */
        }


        .panel a:hover {
            background-color: #00a383;
        }

        .modal-dialog {
            max-width: 400px;
        }

        .modal-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .modal-body {
            font-size: 16px;
            color: #333;
        }

        .modal-footer .btn {
            background-color: #dc3545;
            color: #fff;
            border-radius: 6px;
            padding: 10px 20px;
            transition: background-color 0.2s;
        }

        .modal-footer .btn:hover {
            background-color: #c82333;
        }

        .paragraph{
            margin-top: 20px;
        }
        
    </style>
</head>

<body>
    <header>
        <h1
            style="font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif; padding-top: 30px; font-size: 45px;color: #000000;">
            PAINEL DE CONTROLE</h1>
        <p>Tela exclusiva para administradores</p>
        <hr>
    </header>
    <!-- <div id="center">
        <div class="panel">
            <p style="font-size: 23px; font-weight: bold; text-align: center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">Configurações</p>
            <a style="font-weight: bolder; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" href="ConfigStatus/configStatus.html">CONFIGURAR STATUS</a>

            <a style="font-weight: bolder; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" href="ConfigSetor/ConfigSetor.html">CONFIGURAR SETOR</a>
            <a style="font-weight: bolder; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" href="ConfigSecao/ConfigSecao.html">CONFIGURAR SEÇÃO </a>
            <a style="font-weight: bolder; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" href="ConfigEquipamento/ConfigTipoEquipamento.html">CONFIGURAR TIPO EQUIPAMENTO</a>
        </div>
        <div class="panel">
            <p style="font-size: 23px; font-weight: bold; text-align: center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">Consultas</p>
            <a style="font-weight: bolder;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" href="Consultas/Pessoa.html">PESSOAS</a>
            <a style="font-weight: bolder;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" href="Consultas/Equipamentos.html">EQUIPAMENTOS</a>
            <p style="font-size: 23px; font-weight: bold; text-align: center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;" class="mt-4">Relatórios</p>
            <a style="font-weight: bolder;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" href="../relatorios/index.html">Relatório Index</a>
        </div>
    </div> -->

    <div class="caixas">

        <div style="text-align:center;" class="floating-box"
            onclick="window.location.href='ConfigStatus/configStatus.html'; ">
            <a style="font-weight: bolder; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"
                href="ConfigStatus/configStatus.html">
                <img class="imagens" src="..\Icones Site\chamados.png " alt=" saaeb barretos" height="100">
                <p class="paragraph">CONFIGURAR STATUS</p>
            </a>
        </div>

        <div style="text-align:center;" class="floating-box"
            onclick="window.location.href='ConfigSetor/ConfigSetor.html'; ">
            <a style="font-weight: bolder; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"
                href="ConfigSetor/ConfigSetor.html">
                <img class="imagens" src="..\Icones Site\parede.png " alt=" saaeb barretos" height="100">
                <p class="paragraph">CONFIGURAR SETOR</p>
            </a>
        </div>

        <div style="text-align:center;" class="floating-box"
            onclick="window.location.href='ConfigLocal/ConfigLocal.html'; ">
            <a style="font-weight: bolder; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"
                href="ConfigLocal/ConfigLocal.html">
                <img class="imagens" src="..\Icones Site\local.png " alt=" saaeb barretos" height="100">
                <p class="paragraph">CONFIGURAR LOCAL</p>
            </a>
        </div>

        <div style="text-align:center;" class="floating-box"
            onclick="window.location.href='ConfigEquipamento/TipoEquip.html'; ">
            <a style="font-weight: bolder; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"
                href="ConfigEquipamento/TipoEquip.html">
                <img class="imagens" src="..\Icones Site\computador.png " alt=" saaeb barretos" height="100">
                <p class="paragraph">CONFIGURAR TIPO EQUIPAMENTO</p>
            </a>
        </div>

        <div style="text-align:center" class="floating-box" onclick="window.location.href='Consultas/Pessoa.html'; ">
            <a style="font-weight: bolder;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"
                href="Consultas/Pessoa.html">
                <img class="imagens" src="..\Icones Site\pessoas.png " alt=" saaeb barretos" height="100">
                <p class="paragraph">PESSOAS</p>
            </a>
        </div>

        <div style="text-align:center" class="floating-box"
            onclick="window.location.href='Consultas/Equipamentos.html'; ">
            <a style="font-weight: bolder;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"
                href="Consultas/Equipamentos.html">
                <img class="imagens" src="..\Icones Site\teclado.png " alt=" saaeb barretos" height="100">
                <p class="paragraph">EQUIPAMENTOS</p>
            </a>
            <!-- <p> VENHA REALIZAR A SUA CONFIGURAÇÃO</p> -->
        </div>

        <div style="text-align:center" class="floating-box" onclick="window.location.href='../relatorios/index.html'; ">
            <a style="font-weight: bolder;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"
                href="../relatorios/index.html">
                <img class="imagens" src="..\Icones Site\relatorio.png " alt=" saaeb barretos" height="100">
                <p class="paragraph">RELATÓRIO INDEX</p>
            </a>
        </div>

    </div>
    <!-- <div class="container container-fluid">
    </div> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>