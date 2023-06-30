<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["select_ready_person"])) {
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }
        
        $sql = "SELECT p.IDPessoa, p.nomeCompleto, p.cpf, p.matricula, st.descricao_setor, sc.descricao_secao, p.email, p.gestor
                FROM TBPessoa p
                JOIN TBSetor st ON p.setor = st.IDSetor
                JOIN TBSecao sc ON p.secao = sc.IDSecao";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDPessoa = $row["IDPessoa"];
                $nomeCompleto = $row["nomeCompleto"];
                $cpf = $row["cpf"];
                $matricula = $row["matricula"];
                $setor = $row["descricao_setor"];
                $secao = $row["descricao_secao"];
                $email = $row['email'];
                $gerente = $row['gestor'];

                        // Verifica se $gerente é igual a 1
        if ($gerente == 1) {
            // Usar ícone para gerente
            $icone = "<i class='bi bi-check-square' onclick='onoffGerente(" . $IDPessoa . ")' data-value='" . $gerente . "' id='sqr-" . $IDPessoa . "'></i>";
        } else {
            // Usar ícone padrão
            $icone = "<i class='bi bi-square' onclick='onoffGerente(" . $IDPessoa . ")' data-value='" . $gerente . "' id='sqr-" . $IDPessoa	 . "'></i>";
        }
                echo "<tr>
                <td>".$IDPessoa."</td>
                <td>".$nomeCompleto."</td>
                <td>".$cpf."</td>
                <td>".$matricula."</td>
                <td>".$setor."</td>
                <td>".$secao."</td>
                <td>".$email."</td>
                <td id=".$IDPessoa.">".$icone."</td>
                </tr>";
            }
        }

        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["verifica"])) {
        // $conn = mysqli_connect("localhost", "root", "", "siasb");
        $verifica = $_POST['verifica'];
        $id = $_POST['id'];

        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }
        // $sql = "UPDATE tbstatus_chamado SET descricao = '$editedValue' WHERE IDStatus = '$id'";
        if($verifica == "desmarcado"){
            $sql = "UPDATE tbpessoa SET gestor = b'1' WHERE IDPessoa = '$id';";
        }else
        if($verifica == "marcado"){
            $sql = "UPDATE tbpessoa SET gestor = b'0' WHERE IDPessoa = '$id';";
        }
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

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['select_ready_equipment'])){

        $sql = "
        SELECT e.sti_id, e.descricao, e.ip, te.descricao as 'tipo',p.nomeCompleto, st.descricao_setor, sc.descricao_secao FROM TBEquipamentos e
        join TBTipo_Equipamentos te on te.IDTipo = e.tipo
        join TBPessoa p on p.IDPessoa = e.usuario
        join TBSetor st on e.setor = st.IDSetor
        join TBSecao sc on e.secao = sc.IDSecao
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $sti_id = $row["sti_id"];
                $descricao = $row["descricao"];
                $ip = $row["ip"];
                $tipo = $row["tipo"];
                $nomeCompleto = $row["nomeCompleto"];
                $setor = $row['descricao_setor'];
                $secao = $row["descricao_secao"];

                echo "<tr>
                <td>".$sti_id."</td>
                <td>".$descricao."</td>
                <td>".$ip."</td>
                <td>".$tipo."</td>
                <td>".$nomeCompleto."</td>
                <td>".$setor."</td>
                <td>".$secao."</td>
                </tr>";
            }
        }

        $stmt->close();
        $conn->close();
    }
    }

?>
