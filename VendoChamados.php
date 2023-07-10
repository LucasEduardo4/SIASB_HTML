<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $conn = mysqli_connect("localhost", "root", "", "siasb");

    $dataDe = $_POST['dataDe'];
    $dataAte = $_POST['dataAte'];
    
    // Converter a data para o formato desejado
    $dataDeFormatted = date_format(date_create_from_format('d/m/Y', $dataDe), 'Y/m/d');
    $dataAteFormatted = date_format(date_create_from_format('d/m/Y', $dataAte), 'Y/m/d');
    

    $sql=	"SELECT * FROM TBChamados
    WHERE dataAbertura between '$dataDeFormatted' and '$dataAteFormatted'";

    $result = $conn->query($sql);
    // if ($result && $result->num_rows > 0) {
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $IDChamado = $row["IDChamado"];
            $dataAbertura = $row["dataAbertura"];
            $assunto = $row["assunto"];
            $status_chamado = $row["status_chamado"];
            
            // Adicionar os valores na tabela HTML com ícones de ação
            echo "
            <div class='Filtro Form'>
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
    }
    // echo $_POST['dataOption'];
    // echo "<br>";
    // echo "<br>";
    // echo $_POST['dataDe'];
    // echo "<br>";
    // echo $_POST['dataAte'];
    // echo "<br>";
    // echo $_POST['codigoSolicitacao'];
    // echo "<br>";
    // echo $_POST['status'];
}

?>