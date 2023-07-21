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
        
        
        if($administrador){
            $sql = "SELECT * FROM TBChamados WHERE 1=1"; // Consulta básica ADM:
            // echo "<br> consulta básica ADM";
        }else{
            $sql = "SELECT * FROM TBChamados WHERE autor = '$IDUsuario'"; //Consulta básica Usuário comum
            // echo "<br> consulta básica Uusário Comum";
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
                <div class='CamposResultados' id='IDChamado'> <p style='padding-left: 10px;'>".$IDChamado."</p> </div>
                <div class='CamposResultados'> <p>".$dataAbertura."</p> </div>
                <div class='CamposResultados'> <p>".$assunto."</p> </div>
                <div class='CamposResultados'> <p>".$status_chamado."</p> </div>
                <div class='CamposResultados responsavel_tecnologia'> <h1 class='bi bi-gear' onclick='detalharChamado(this)'></h1> </div>
                </div>
                ";
            }
        } else {
            echo "Nenhum resultado encontrado para este usuário.";
        }
    }
}

if($_SERVER['REQUEST_METHOD']=== 'POST'){
    if(isset($_POST['verificarSetor'])){
        $conn = mysqli_connect("localhost", "root", "", "siasb");
        $IDUsuario = $_SESSION['username'];
        $sql = "SELECT p.setor FROM TBUsuario u
                join tbpessoa p on p.IDPessoa = u.IDUsuario
                where u.nome = '$IDUsuario'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $setor = $row["setor"];
                    if($setor == '1'){
                        echo "1";
                    }else
                        echo "0";
                }
            }
        }
        // http://10.0.0.118:9090/siasb_html/VendoChamados.html
        // echo $result;
    }
}
?>