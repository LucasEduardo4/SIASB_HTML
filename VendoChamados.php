<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function convertData($data)
    {
        $dataHora = DateTime::createFromFormat('Y-m-d H:i:s', $data);
        if ($dataHora === false) {
            $dataHora = DateTime::createFromFormat(('Y-m-d'), $data);
            if ($dataHora === false) {
                return "--";
            }
        }

        return $dataHora->format('d/m/y');
    }
    if (isset($_POST['Select'])) {
        $conn = mysqli_connect("localhost", "root", "", "siasb");
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM tbusuario u join tbpessoa p on u.IDUsuario = p.IDPessoa where u.nome = '$username'";

        $setor_secao = 0;

        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $IDUsuario = $row["IDUsuario"];
                    $nome = $row["nome"];
                    $setor_secao = $row["setor_secao"];
                    // Adicionar os valores na tabela HTML com ícones de ação
                }
            } else {
                $setor_secao = 0;
            }
        } else {
            echo "Erro na consulta SQL: " . $conn->error;
        }
        // IDChamado, dataAbertura, assunto, status_chamado

        $sql = "SELECT IDChamado, dataAbertura, assunto, sc.descricao as 'status_chamado' FROM TBChamados"; // Consulta básica ADM:

        $sql .= " join tbstatus_chamado sc on status_chamado = sc.IDStatus";

        if ($setor_secao == 1) { //setor_secao 1 == tecnologia
            $sql .= " WHERE 1=1"; // Consulta básica ADM:
        } else {
            $sql .= " WHERE autor = '$IDUsuario'"; //Consulta básica Usuário comum
        }

        $dataOption = $_POST['dataOption'];
        $dataDe = $_POST['dataDe'];
        $dataAte = $_POST['dataAte'];

        if ($_POST['dataDe'] != '' && $_POST['dataAte'] != '') {
            $dataDeFormatted = date_format(date_create_from_format('d/m/Y', $dataDe), 'Y/m/d');
            $dataAteFormatted = date_format(date_create_from_format('d/m/Y', $dataAte), 'Y/m/d');
            $dataAteFormatted = date('Y-m-d H:i:s', strtotime($dataAteFormatted . ' +1 day'));
        }

        $codigoSolicitacao = $_POST['codigoSolicitacao'];
        $status = $_POST['status'];

        if ($codigoSolicitacao != '') {
            $sql .= " AND IDChamado = '$codigoSolicitacao'";
        }

        if ($dataOption == 1) {
            if ($dataDe == '' || $dataAte == '') {
                $dataDeFormatted = '2023-01-01';
                $dataAteFormatted = '2023-01-01';
            }
            $sql .= " AND dataAbertura BETWEEN '$dataDeFormatted' AND '$dataAteFormatted'";
        }

        if ($dataOption == 2) {
            if ($dataDe == '' || $dataAte == '') {
                $dataDeFormatted = '2023-01-01';
                $dataAteFormatted = '2023-01-01';
            }
            $sql .= " AND dataFechamento BETWEEN '$dataDeFormatted' AND '$dataAteFormatted'";
        }
        if ($status == 'inicial') {
            $sql .= " AND status_chamado != '4'";

        } else
            if ($status != 'inicial' && $status != 5) {
                $sql .= " AND status_chamado = '$status'";
            } else
                if ($status == 5) {
                    // $sql .= " AND status_chamado = *";
                    $sql .= " ORDER BY IDChamado ASC";
                    // echo $sql;
                }

        // Executa a consulta SQL
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDChamado = $row["IDChamado"];
                $dataAbertura = $row["dataAbertura"];
                $assunto = $row["assunto"];
                $status_chamado = $row["status_chamado"];
                // <div class='cells' id='IDChamado'> <p style='padding-left: 10px;'>" . $IDChamado . "</p> </div>

                // Adicionar os valores na tabela HTML com ícones de ação
                echo "
                <a href='detalhandoChamado.html?IDChamado=" . $IDChamado . "'>
                <div class='FaixaFormResults'>
                <div class='cells'><p>" . convertData($dataAbertura) . "</p></div>
                <div class='cells'><p>" . $assunto . "</p></div>
                <div class='cells'><p class='statusColors $status_chamado'>" . $status_chamado . "</p></div>
                </div>
                </a>
                ";
                // <div class='CamposResultados responsavel_tecnologia'> <h1 class='bi bi-gear' onclick='detalharChamado(this)'></h1> </div>
            }
        } else {
            echo "Nenhum resultado encontrado para este usuário.";
        }
     
    }
}

?>