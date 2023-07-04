<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if($_POST['selectStatus'] == 1){
        $conn = mysqli_connect("localhost", "root", "", "siasb");

    
        $sql = "SELECT IDStatus, descricao FROM tbstatus_chamado";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
            $idValue = $row["IDStatus"];
            $descValue = $row["descricao"];
            
            // Adicionar os valores na tabela HTML com ícones de ação
            echo "
            <option value=".$idValue.">".$descValue."</option>
            ";
        }
    } else {
        echo "Nenhum resultado encontrado.";
    }
    $conn->close(); 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if($_POST['selectStatus']){
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $statusSelected = $_POST['selectStatus'];
        $sql = "SELECT * FROM tbchamados WHERE status_chamado = $statusSelected";
        $result = $conn->query($sql);

            if ($result->num_rows > 0) {
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
}

?>

	
	
	
	
	
	
	
