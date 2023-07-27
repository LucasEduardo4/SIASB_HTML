<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['Select'])){
        $conn = mysqli_connect("localhost", "root", "", "siasb");
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM tbusuario where nome = '$username'";
        $administrador = 0;

        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $IDUsuario = $row["IDUsuario"];
                    $nome = $row["nome"];
                    $administrador = $row["administrador"];
                    // Adicionar os valores na tabela HTML com ícones de ação
                }
            } else {
                $administrador = 0;
            }
        } else {
            echo "Erro na consulta SQL: " . $conn->error;
        }
        // IDChamado, dataAbertura, assunto, status_chamado
        
        $sql = "SELECT IDChamado, dataAbertura, assunto, sc.descricao as 'status_chamado' FROM TBChamados"; // Consulta básica ADM:

        $sql .= " join tbstatus_chamado sc on status_chamado = sc.IDStatus";

        if($administrador){
            $sql .= " WHERE 1=1"; // Consulta básica ADM:
        }else{
            $sql .= " AND autor = '$IDUsuario'"; //Consulta básica Usuário comum
        }

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
        if($status == 'inicial'){
            $sql .= " AND status_chamado != '4'";

        }else        
        if ($status != 'inicial' && $status != 5) {
            $sql .= " AND status_chamado = '$status'";
        }else
        if ($status == 5){
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
 
                // Adicionar os valores na tabela HTML com ícones de ação
                echo "
                <a href='detalhandoChamado.html?IDChamado=".$IDChamado."'>
                <div class='FaixaForm'>
                <div class='CamposResultados' id='IDChamado'> <p style='padding-left: 10px;'>".$IDChamado."</p> </div>
                <div class='CamposResultados'> <p>".$dataAbertura."</p> </div>
                <div class='CamposResultados'> <p>".$assunto."</p> </div>
                <div class='CamposResultados'> <p>".$status_chamado."</p> </div>
                </div>
                </a>
                ";
                // <div class='CamposResultados responsavel_tecnologia'> <h1 class='bi bi-gear' onclick='detalharChamado(this)'></h1> </div>
            }
        } else {
            // echo $sql;
            // echo "<br>";
            echo "Nenhum resultado encontrado para este usuário.";
        }
    }
}

?>