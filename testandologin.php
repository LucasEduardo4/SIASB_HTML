<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../icons/warning.png" sizes="192x192" />
    <link rel="stylesheet" href="../../sidebars/bootstrap.min.css">
    <link rel="stylesheet" href="../lib/stylesheet.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="./../../flowSite/verificaSessao.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 10px;
            background-color: #32a852;
            color: #fff;
            font-size: 20px;
        }

        header a {
            color: #fff;
            text-decoration: none;
        }

        header a:hover {
            color: #d4d4d4;
        }

        header i {
            margin-right: 5px;
        }

        h3 {
            color: #32a852;
            font-size: 24px;
            margin-top: 20px;
        }

        form {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        label {
            color: #32a852;
            font-size: 16px;
            margin-right: 10px;
        }

        input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #32a852;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button i {
            margin-right: 5px;
        }

        button:hover {
            background-color: #2d944a;
        }

        table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table th,
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #32a852;
            color: #fff;
            font-size: 16px;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #d4d4d4;
        }

        table caption {
            color: #32a852;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        #resultado {
            color: #32a852;
            margin-top: 20px;
            font-size: 16px;
        }

        #resultado h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        #resultado p {
            font-size: 16px;
        }
    </style>

    <title>Configurar Status</title>
</head>

<header>
    <div>
        <a href="../index.html">
            <i class="bi-arrow-left-circle bi-lg"></i>
            <span>Voltar</span>
        </a>
    </div>
</header>

<body onload="init()">
    <h3>Configurar Status Chamado</h3>
    <hr>
    <form>
        <label for="novoStatus">Adicionar novo status:</label>
        <input type="text" name="novoStatus" required>
        <button onclick="submitForm()"><i class="bi bi-plus"></i>Adicionar</button>
    </form>
    <table id="tableStatus">
        <caption>Status Existentes:</caption>
        <thead>
            <tr>
                <th>ID Status</th>
                <th>Descrição status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div id="resultado">
        <h3>Tela funcionando 100%</h3>
        <p>Mas existem algumas alterações válidas:</p>
        <ul>
            <li>Adicionar uma verificação se quer realmente excluir</li>
        </ul>
    </div>
    <script>
        // Restante do seu script permanece inalterado
    </script>
</body>

</html>
