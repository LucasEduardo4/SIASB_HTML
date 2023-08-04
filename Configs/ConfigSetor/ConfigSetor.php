<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["select_ready"])) {
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }
        
        $sql = "Select IDSetor, descricao_setor, p.nomeCompleto from TBSetor
        join TBPessoa p on gerente = p.IDPessoa";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDSetor = $row["IDSetor"];
                $descricao = $row["descricao_setor"];
                $gerente = $row["nomeCompleto"];


                echo "<tr>
                <td>".$IDSetor."</td>
                <td style='padding-left:90px'>".$descricao."</td>
                <td>".$gerente."</td>
                </tr>";
                
            }
        }

        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["gerente"])) {
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }
        
        $sql = "SELECT distinct IDPessoa, nomeCompleto
        from TBPessoa
        where gestor =1";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDPessoa = $row["IDPessoa"];
                $gerente = $row["nomeCompleto"];


                echo"<option disabled selected hidden>-------------------------</option>
                <option value='".$IDPessoa."'>".$gerente."</option>";
            }
        }

        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["nomeSetor"]) && isset($_POST["responsavel"])) {
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $nomeSetor = $_POST["nomeSetor"];
        $gerente = $_POST["responsavel"];
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $sql = "
        SET @UltimoIDSetor = (SELECT MAX(IDSetor) FROM tbsetor);
        SET @UltimoIDSetor = IFNULL(@UltimoIDSetor, 0) + 1;
        INSERT INTO tbsetor (IDSetor, descricao_setor, gerente)
        VALUES (@UltimoIDSetor, '$nomeSetor', '$gerente');
        ";

        if (mysqli_multi_query($conn, $sql)) {
            echo "Novo setor adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar novo status: " . mysqli_error($conn);
        }

        $conn->close();
    }
}



?>
