<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = mysqli_connect("localhost", "root", "", "siasb");
    $sql = "SELECT * FROM TBChamados WHERE 1=1"; // Inicia com uma consulta básica

    $dataOption = $_POST['dataOption'];
    $dataDe = $_POST['dataDe'];
    $dataAte = $_POST['dataAte'];

    if($_POST['dataDe'] != '' && $_POST['dataAte'] != ''){
        $dataDeFormatted = date_format(date_create_from_format('d/m/Y', $dataDe), 'Y/m/d');
        $dataAteFormatted = date_format(date_create_from_format('d/m/Y', $dataAte), 'Y/m/d');
        $dataAteFormatted = date('Y-m-d H:i:s', strtotime($dataAteFormatted . ' +1 day'));
    }

    $codigoSolicitacao = $_POST['codigoSolicitacao'];
    $status = $_POST['status'];

    if ($codigoSolicitacao != '') {
        $sql .= " AND IDChamado = '$codigoSolicitacao'";
    }

    if ($dataOption == 1 || $dataOption == 3) {
        $sql .= " AND dataAbertura BETWEEN '$dataDeFormatted' AND '$dataAteFormatted'";
    }

    if ($dataOption == 2) {
        $sql .= " AND dataFechamento BETWEEN '$dataDeFormatted' AND '$dataAteFormatted'";
    }

    if ($status != '') {
        $sql .= " AND status_chamado = '$status'";
    }

    // Executa a consulta SQL
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $IDChamado = $row["IDChamado"];
            $dataAbertura = $row["dataAbertura"];
            $assunto = $row["assunto"];
            $status_chamado = $row["status_chamado"];
            
            // Adicionar os valores na tabela HTML com ícones de ação
            echo "
            <div class='FaixaForm'>
            <div class='CamposResultados'> <p style='padding-left: 10px;'>".$IDChamado."</p> </div>
            <div class='CamposResultados'> <p>".$dataAbertura."</p> </div>
            <div class='CamposResultados'> <p>".$assunto."</p> </div>
            <div class='CamposResultados'> <p>".$status_chamado."</p> </div>
            </div>
            ";
        }
    } else {
        echo "Nenhum resultado encontrado.";
        echo "<br> Select realizado -> ";
        echo $sql;
        echo "<br>";
        echo "<br>";
        echo $_POST['dataOption'];
        echo "<br>";
        echo $_POST['dataDe'];
        echo "<br>";
        echo $_POST['dataAte'];
        echo "<br>";
        echo $_POST['codigoSolicitacao'];
        echo "<br>";
        echo $_POST['status'];
    }
}

?>


<!-- 
    
    if(isset($_POST['codigoSolicitacao'])){
    $CodigoSolicitacao = $_POST['codigoSolicitacao'];
    $sql=	"SELECT * FROM TBChamados
    WHERE IDCHamado = '$CodigoSolicitacao'";
    } -->