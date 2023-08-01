<?php
function convertData($data) {
    $dataHora = DateTime::createFromFormat('Y-m-d H:i:s', $data);
    if ($dataHora === false) {
        return "--";
    }
    
    return $dataHora->format('d/m/y');
}
// Verifica se a requisição é do tipo POST
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['SelectInicial'])){
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $resultArray = array();

        //responsavel
        $sql1 = "SELECT u.IDUsuario, p.nomeCompleto FROM TBUsuario u
        left join tbpessoa p on u.IDUsuario = p.IDPessoa
        WHERE p.setor = 1;";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        $responsavel = array();
        while ($row1 = $result1->fetch_assoc()) {
            $IDUsuario = $row1["IDUsuario"];
            $nomeCompleto = $row1["nomeCompleto"];
            $responsaveis[$IDUsuario] = $nomeCompleto;
        }
        $resultArray['responsaveis'] = $responsaveis;
        
        // Consulta 2: SELECT * FROM TBSetor
        $sql2 = "SELECT DISTINCT u.IDUsuario, u.nome FROM tbchamados c
        left join tbusuario u on c.autor = u.IDUsuario";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $usuario = array();
        while ($row2 = $result2->fetch_assoc()) {
            $IDUsuario = $row2["IDUsuario"];
            $nome = $row2["nome"];
            $usuarios[$IDUsuario] = $nome;
        }
        $resultArray['usuarios'] = $usuarios;

        $stmt1->close();
        $stmt2->close();

        
        $sql3 = "SELECT DISTINCT * FROM tbstatus_chamado";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->execute();
        $result3 = $stmt3->get_result();

        $status = array();

        while ($row3 = $result3->fetch_assoc()) {
            $IDStatus = $row3["IDStatus"];
            $descricao = $row3["descricao"];
            $status[$IDStatus] = $descricao;
        }
        $resultArray['status'] = $status;
        $stmt3->close();
        

        $sql4 = "SELECT DISTINCT * FROM tbtipo_equipamentos";
        $stmt4 = $conn->prepare($sql4);
        $stmt4->execute();
        $result4 = $stmt4->get_result();
        
        
        $tipo_equipamento = array();

        while ($row4 = $result4->fetch_assoc()) {
            $IDTipo = $row4["IDTipo"];
            $descricao = $row4["descricao"];
            $tipo_equipamento[$IDTipo] = $descricao;
        }

        $resultArray['tipo_equipamento'] = $tipo_equipamento;
        $stmt4->close();

        $sql5 = "SELECT DISTINCT * FROM tbsetor";
        $stmt5 = $conn->prepare($sql5);
        $stmt5->execute();
        $result5 = $stmt5->get_result();

        $setor = array();

        while ($row5 = $result5->fetch_assoc()) {
            $IDSetor = $row5["IDSetor"];
            $descricao_setor = $row5["descricao_setor"];
            $setor[$IDSetor] = $descricao_setor;
        }

        $resultArray['setor'] = $setor;
        $stmt5->close();

        $conn->close();
        // Retornar o resultado como JSON
        echo json_encode($resultArray);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['filtroDataAbertura'])){

    
    $conn = mysqli_connect("localhost", "root", "", "siasb");

    // Recupera os dados do formulário
    $filtroDataAbertura = $_POST['filtroDataAbertura'] ?? null;
    $filtroDataFechamento = $_POST['filtroDataFechamento'] ?? null;
    $filtroResponsavel = $_POST['filtroResponsavel'] ?? null;
    $filtroAutor = $_POST['filtroAutor'] ?? null;
    $filtroStatus = $_POST['filtroStatus'] ?? null;
    $filtroTipoEquipamento = $_POST['filtroTipoEquipamento'] ?? null;
    $filtroSetor = $_POST['filtroSetor'] ?? null;
    $ordenarPor = $_POST['ordenar'] ?? null;
    $ordem = $_POST['ordem'] ?? null;
    $agruparPor = $_POST['agruparPor'] ?? null;

    // Agora você pode usar esses dados para construir sua consulta SQL
    // Por exemplo:
    $sql = "SELECT * FROM tbchamados WHERE 1=1";

    if (!empty($filtroDataAbertura)) {
        $sql .= " AND dataAbertura >= '$filtroDataAbertura'";
    }

    if (!empty($filtroDataFechamento)) {
        // Adiciona um dia à data final para incluir também a data selecionada
        $dataFinal = date('Y-m-d H:i:s', strtotime($filtroDataFechamento . ' +1 day'));
        $sql .= " AND dataFechamento <= '$dataFinal'";
    }

    if (!empty($filtroResponsavel)) {
        $sql .= " AND responsavel = '$filtroResponsavel'";
    }

    if (!empty($filtroAutor)) {
        $sql .= " AND autor = '$filtroAutor'";
    }

    if (!empty($filtroStatus)) {
        if ($filtroStatus != 5) {
            $sql .= " AND status_chamado = '$filtroStatus'";
        }
    }
    if (!empty($filtroTipoEquipamento)) {
        $sql .= " AND equipamento = '$filtroTipoEquipamento'";
    }

    if (!empty($filtroSetor)) {
        $sql .= " AND setor = '$filtroSetor'";
    }

    // Ordenação
    if (!empty($ordenarPor)) {
        $sql .= " ORDER BY $ordenarPor";
        if ($ordem === 'decrescente') {
            $sql .= " DESC";
        }
    }
    if(!empty($agruparPor)){
        $sql .= " GROUP BY $agruparPor";
    }
    $sql .= ' limit 10';
    // echo $sql;
    // $resultado = $conn->query($sql);

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->error) {
        echo $stmt->error;
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $IDChamado = $row['IDChamado'];
            $assunto = $row['assunto'];
            $descricao = $row['descricao'];
            $dataAbertura = convertData($row['dataAbertura']);
            $dataFechamento = convertData($row['dataFechamento']);
            $responsavel = $row['responsavel'];
            $equipamento = $row['equipamento'];
            $autor = $row['autor'];
            $status_chamado = $row['status_chamado'];

            // echo "IDChamado = $IDChamado <br>
            // assunto = $assunto <br>
            // descricao = $descricao <br>
            // dataAbertura = $dataAbertura <br>
            // dataFechamento = $dataFechamento <br>
            // responsavel = $responsavel <br>
            // equipamento = $equipamento <br>
            // autor = $autor <br>
            // status_chamado = $status_chamado <br><br>
            // <br> <br>";

            echo "<tr>
            <td>$IDChamado</td>
            <td>$assunto</td>
            <td>$dataAbertura</td>
            <td>$dataFechamento</td>
            <td>$responsavel</td>
            <td>$equipamento</td>
            <td>$autor</td>
            <td>$status_chamado</td>
            </tr>";
        }
    }else{
        $response = "Erro na consulta: " . mysqli_error($conn);
    }


    // $stmt->close();
    // $conn->close();

    // Executar a consulta no banco de dados e gerar o relatório em PDF ou exibir na página,
    // dependendo da lógica do seu sistema.

    // Exemplo de como gerar o relatório em PDF usando TCPDF (assumindo que você já configurou a conexão com o banco de dados):
    require('tcpdf/tcpdf.php');

    // Crie um objeto TCPDF e configure o cabeçalho e rodapé, se necessário.
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Execute a consulta e itere sobre os resultados para construir o relatório.
    // Por exemplo:
    while ($row = $result->fetch_assoc()) {
        // Construa o conteúdo do relatório usando $row['nome_do_campo'] para acessar os dados do banco de dados.
        // Adicione as células do TCPDF conforme necessário.
        // $pdf->Cell(...);
    }

    // Saída do relatório em PDF
    // $pdf->Output('relatorio.pdf', 'I');
    }
}

?>