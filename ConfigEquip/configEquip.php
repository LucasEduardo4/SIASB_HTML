<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST["novoStatus"])) {
        $novoStatus = $_POST['novoStatus'];

        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $sql = "INSERT INTO TBStatus_chamado (descricao) VALUES ('$novoStatus')";

        if ($conn->query($sql) === true) {
            echo "Novo status adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar novo status: " . $conn->error;
        }

        $conn->close();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    // $conn = mysqli_connect("localhost", "root", "", "siasb");
    $sql = "SELECT IDStatus, descricao FROM tbstatus_chamado";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $idValue = $row["IDStatus"];
            $descValue = $row["descricao"];

            // Adicionar os valores na tabela HTML com ícones de ação
            echo "<tr><td>".$idValue."</td><td id='desc-".$idValue."'>".$descValue."</td><td>
            <span class='glyphicon glyphicon-pencil' aria-hidden='true' id='edit-btn-".$idValue."'></span> 
            <span class='glyphicon glyphicon-trash' aria-hidden='true' id='trash-btn-".$idValue."'></span></span>
            <span class='glyphicon glyphicon-floppy-disk' aria-hidden='true' id='save-btn-".$idValue."'></span></td></tr>";
            // echo "<tr><td>".$idValue."</td><td id='desc-".$idValue."'>".$descValue."</td><td><a href='editar.php?id=".$idValue."'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a> <a href='excluir.php?id=".$idValue."'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td></tr>";
        }
    } else {
    echo "Nenhum resultado encontrado.";
}
    $conn->close(); 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["editedValue"], $_POST["id"])) {
        // $conn = mysqli_connect("localhost", "root", "", "siasb");
        $editedValue = $_POST['editedValue'];
        $id = $_POST['id'];

        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $sql = "UPDATE tbstatus_chamado SET descricao = '$editedValue' WHERE IDStatus = '$id'";

        if ($conn->query($sql) === TRUE) {
            // Atualização bem-sucedida
            echo json_encode(array("success" => true, "message" => "Status atualizado com sucesso!"));
        } else {
            // Erro ao executar a consulta SQL
            echo json_encode(array("success" => false, "message" => "Erro ao atualizar o status: " . $conn->error));
        }

        $conn->close();
    }
}
// DELETE FROM `tbstatus_chamado` WHERE `tbstatus_chamado`.`IDStatus` = 8
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["deletedId"])) {
        $deletedId = $_POST['deletedId'];

        $sql = "DELETE FROM tbstatus_chamado WHERE IDStatus = '$deletedId'";

        if ($conn->query($sql) === TRUE) {
            // Atualização bem-sucedida
            echo json_encode(array("success" => true, "message" => "Status atualizado com sucesso!"));
        } else {
            // Erro ao executar a consulta SQL
            echo json_encode(array("success" => false, "message" => "Erro ao atualizar o status: " . $conn->error));
        }

        $conn->close();
    }
}
?>
