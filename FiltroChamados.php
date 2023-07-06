<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // print_r($_GET); //teste <
    $conn = mysqli_connect("localhost", "root", "", "siasb");
    $sql = '';
    if (isset($_GET['status'])) {
        $sql = "SELECT IDStatus, descricao FROM tbstatus_chamado";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {//da pra transformar isso aqui em uma função
            while ($row = $result->fetch_assoc()) {
                $idValue = $row["IDStatus"];
                $descValue = $row["descricao"];
                echo "<option value='$idValue'>$descValue</option>";
        }
        } else {
            echo "Nenhum resultado encontrado.";
        }
    } else 
    if (isset($_GET['usuario'])) {
        $sql = "SELECT IDUsuario, nome FROM tbusuario";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDUsuario = $row["IDUsuario"];
                $nome = $row["nome"];
                echo "<option value='$IDUsuario'>$nome</option>";
            }
        }
    $conn->close();
    }else
    if(isset($_GET['analista'])){
        $sql = "SELECT IDAdministrador, nome from tbAdministrador";
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDAdministrador = $row["IDAdministrador"];
                $nome = $row["nome"];
                echo "<option value='$IDAdministrador'>$nome</option>";
            }
        }
    }
    else{
        echo "Nada a ser exibido";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $conn = mysqli_connect("localhost", "root", "", "siasb");
    $coluna = $_POST['coluna'];

    if(isset($_POST['filterStatus'])){
        $statusSelected = $_POST['filterStatus'];
        $sql = "SELECT * FROM tbchamados WHERE status_chamado = '$statusSelected'";
    }else
    if(isset($_POST['filterUser'])){
        $userSelected = $_POST['filterUser'];
        $sql= "SELECT * FROM tbchamados WHERE autor = '$userSelected'";
    }else
    if(isset($_POST['filterAnalista'])){
        $analistaSelected = $_POST['filterAnalista'];
        $sql = "SELECT * FROM tbchamados WHERE responsavel = '$analistaSelected'";
    }
        
    $result = $conn->query($sql);
    // if ($result && $result->num_rows > 0) {
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $IDChamado = $row["IDChamado"];
            $assunto = $row["assunto"];
            $descricao = $row["descricao"];
            $dataAbertura = $row["dataAbertura"];
            $status_chamado = $row["status_chamado"];
            $responsavel = $row["responsavel"];
            $autor = $row["autor"];
            $equipamento = $row["equipamento"];
            $imagem = $row["imagem"];
            $categoria = $row["categoria"];
            
            // Adicionar os valores na tabela HTML com ícones de ação
            echo "
            <tr>
            <td>".$IDChamado."</td>
            <td>".$assunto."</td>
            <td>".$descricao."</td>
            <td>".$dataAbertura."</td>
            <td>".$status_chamado."</td>
            <td>".$responsavel."</td>
            <td>".$autor."</td>
            <td>".$equipamento."</td>
            <td>".$imagem."</td>
            <td>".$categoria."</td>
            </tr>
            ";
        }
    } else {
        echo "Nenhum resultado encontrado.";
    }
    $conn->close(); 
}
?>

	
	
	
	
	
	
	
