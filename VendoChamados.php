<?php
session_start();
// var_dump($_SESSION);
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
    $conn = mysqli_connect("localhost", "root", "", "siasb");
    $username = $_SESSION['username'];
    if (isset($_POST['Select']) && $_POST['Select'] == '1') {
        $sql = "SELECT * FROM tbusuario u join tbpessoa p on u.IDUsuario = p.IDPessoa where u.nome = '$username'";

        $setor_secao = 0;

        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $IDUsuario = $row["IDUsuario"];
                    $nome = $row["nome"];
                    $setor_secao = $row["setor_secao"];
                    $_SESSION['setor_secao'] = $setor_secao;
                }
            } else {
                $setor_secao = 0;
            }
        } else {
            echo "Erro na consulta SQL: " . $conn->error;
        }
        // IDChamado, dataAbertura, assunto, status_chamado

        $sql = "SELECT IDChamado, dataAbertura, assunto, sc.descricao as 'status_chamado', p.nomeCompleto as 'autor', p2.nomeCompleto as 'responsavel', equipamento FROM TBChamados"; // Consulta básica ADM:

        $sql .= "   left join tbstatus_chamado sc on status_chamado = sc.IDStatus
                    left join tbpessoa p on autor = p.IDPessoa
                    left join tbpessoa p2 on responsavel = p2.IDPessoa";

        if ($setor_secao == 1) { //setor_secao 1 == tecnologia
            $sql .= " WHERE 1=1"; // Consulta básica ADM:
        } else {
            $sql .= " WHERE autor = '$IDUsuario'"; //Consulta básica Usuário comum
        }

        $dataDe = $_POST['dataDe'];
        $dataAte = $_POST['dataAte'];

        if ($_POST['dataDe'] != '' && $_POST['dataAte'] != '') {
            $dataDeFormatted = date_format(date_create_from_format('d/m/Y', $dataDe), 'Y/m/d');
            $dataDeFormatted = date('Y-m-d H:i:s', strtotime($dataDeFormatted));
            $dataAteFormatted = date_format(date_create_from_format('d/m/Y', $dataAte), 'Y/m/d');
            $dataAteFormatted = date('Y-m-d H:i:s', strtotime($dataAteFormatted . ' +1 day'));
        }

        $codigoSolicitacao = $_POST['codigoSolicitacao'];

        if ($codigoSolicitacao != '') {
            $sql .= " AND IDChamado = '$codigoSolicitacao'";
        }

        $dataOption = $_POST['dataOption'];

        if ($dataOption == 1) {
            if ($dataDe == '' || $dataAte == '') {
                $dataDeFormatted = '2023-01-01';
                $dataAteFormatted = '2023-01-01';
            }
            $sql .= " AND dataAbertura BETWEEN '$dataDeFormatted' AND '$dataAteFormatted'";
        } else
            if ($dataOption == 2) {
                if ($dataDe == '' || $dataAte == '') {
                    $dataDeFormatted = '2023-01-01';
                    $dataAteFormatted = '2023-01-01';
                }
                $sql .= " AND dataFechamento BETWEEN '$dataDeFormatted' AND '$dataAteFormatted'";
            }

        $status = $_POST['status'];

        if ($status == 'inicial') {
            $sql .= " AND status_chamado != '4'";

        } else
            if ($status == 5) {
                $sql .= " AND status_chamado != 0";
                // $sql .= " ORDER BY IDChamado ASC";
            } else
                $sql .= " AND status_chamado =  " . $_POST['status'];

        $autor = $_POST['autor'];
        if ($autor != '') {
            $sql .= " AND autor = '$autor'";
        }

        $responsavel = $_POST['responsavel'];
        if ($responsavel != '') {
            $sql .= " AND responsavel = '$responsavel'";
        }

        $equipamento = $_POST['stiID'];
        if ($equipamento != '') {
            $sql .= " AND equipamento = '$equipamento'";
        }

        $coluna = $_POST['ordenar'];
        $ordem = $_POST['ordem'];

        if ($coluna != '') {
            $sql .= " ORDER BY $coluna $ordem";
        } else {
            $sql .= " ORDER BY IDChamado ASC";
        }
        // echo $sql;
        // var_dump($_POST);
        // Executa a consulta SQL
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDChamado = $row["IDChamado"];
                $dataAbertura = $row["dataAbertura"];
                $assunto = $row["assunto"];
                $status_chamado = $row["status_chamado"];
                $autor = $row["autor"];
                $responsavel = $row["responsavel"];
                $equipamento = $row["equipamento"];
                // <div class='cells' id='IDChamado'> <p style='padding-left: 10px;'>" . $IDChamado . "</p> </div>

                // Adicionar os valores na tabela HTML com ícones de ação
                echo "
                <a href='detalhandoChamado.html?IDChamado=" . $IDChamado . "'>
                    <div class='FaixaFormResults'>
                        <div class='cells'><p>" . convertData($dataAbertura) . "</p></div>
                        <div class='cells'><p>" . $assunto . "</p></div>
                        <div class='cells'><p>" . $autor . "</p></div>
                        <div class='cells'><p>" . $responsavel . "</p></div>
                        <div class='cells'><p>" . $equipamento . "</p></div>
                        <div class='cells'><span class='statusColors $status_chamado'>" . $status_chamado . "</span></div>
                    </div>
                </a>
                ";
                // <div class='CamposResultados responsavel_tecnologia'> <h1 class='bi bi-gear' onclick='detalharChamado(this)'></h1> </div>
            }
        } else {
            echo "
            <div class='FaixaFormResults'>
                <div class='cells'><p></p></div>
                <div class='cells'><h4 style='text-align:center; margin-top:6px;vertical-align:top'>Sem Resultado!</h4></div>
                <div class='cells'><p></p></div>
            </div>
            ";
        }

    } else
        if (isset($_POST['Select']) && $_POST['Select'] == '2') {
            $setor_secao = $_SESSION['setor_secao'];
            // $sql = "Select * from tbusuario LEFT JOIN tbpessoa on tbusuario.IDUsuario = tbpessoa.IDPessoa";
            if ($setor_secao == 1)
                $sql = "SELECT DISTINCT p.IDPessoa, p.nomeCompleto FROM tbchamados c left join tbusuario u on c.autor = u.IDUsuario left join tbpessoa p on u.IDUsuario = p.IDPessoa";
            else
                $sql = "SELECT DISTINCT p.IDPessoa, p.nomeCompleto FROM tbchamados c left join tbusuario u on c.autor = u.IDUsuario left join tbpessoa p on u.IDUsuario = p.IDPessoa where u.nome = '$username'";
            // $sql = "SELECT DISTINCT p.IDPessoa, p.nomeCompleto FROM tbchamados c left join tbusuario u on c.autor = u.IDUsuario left join tbpessoa p on u.IDUsuario = p.IDPessoa where u.nome = '$username'";


            $usuarios = array();

            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $IDUsuario = $row["IDPessoa"];
                    $nome = $row["nomeCompleto"];

                    $usuarios[] = array("IDUsuario" => $IDUsuario, "nome" => $nome);

                }
            }

            // $sql2 = "SELECT * from tbusuario u LEFT JOIN tbpessoa p on u.IDUsuario = p.IDPessoa where p.setor_secao = 1";
            $sql2 = "SELECT DISTINCT p.IDPessoa, p.nomeCompleto FROM tbchamados c left join tbusuario u on c.responsavel = u.IDUsuario left join tbpessoa p on u.IDUsuario = p.IDPessoa where p.setor_secao = 1";
            $responsaveis = array();
            $result2 = $conn->query($sql2);
            if ($result2 && $result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
                    $IDUsuario = $row["IDPessoa"];
                    $nome = $row["nomeCompleto"];

                    $responsaveis[] = array("IDUsuario" => $IDUsuario, "nome" => $nome);

                }
            }

            $sql3 = "SELECT * FROM TBStatus_chamado";
            $status = array();
            $result3 = $conn->query($sql3);

            if ($result3 && $result3->num_rows > 0) {
                while ($row = $result3->fetch_assoc()) {
                    $IDStatus = $row["IDStatus"];
                    $descricao = $row["descricao"];

                    $status[] = array("IDStatus" => $IDStatus, "descricao" => $descricao);

                }
            }

            $resultJSON = array();
            $resultJSON['usuarios'] = $usuarios;
            $resultJSON['responsaveis'] = $responsaveis;
            $resultJSON['status'] = $status;

            echo json_encode($resultJSON);
        } else
            if (isset($_POST['Select']) && $_POST['Select'] == '3') {
                $stiID = $_POST['stiID'];
                $descricao = '';
                $sql = "SELECT descricao from tbequipamentos WHERE sti_id = $stiID";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        $descricao = $row['descricao'];
                    } else
                        $descricao = 'Equipamento nao encontrado';
                } else {
                    echo "Digite um STI_ID valido (números inteiros)";
                }
                echo $descricao;
            }
}

?>