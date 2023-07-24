<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["select_ready"])) {
        $sql = "SELECT s.IDSecao, s.descricao_secao, p.nomeCompleto, t.descricao_setor FROM TBSECAO s
        join TBPessoa p on s.gerente = p.IDPessoa
        join TBSetor t on s.setor = t.IDSetor";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDSecao = $row["IDSecao"];
                $descricao = $row["descricao_secao"];
                $gerente = $row["nomeCompleto"];
                $setor = $row["descricao_setor"];

                echo "<tr>
                <td>".$IDSecao."</td>
                <td>".$descricao."</td>
                <td>".$gerente."</td>
                <td>".$setor."</td>
                </tr>";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["selects"])) {
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $resultArray = array();

        // Consulta 1: SELECT distinct IDPessoa, nomeCompleto FROM TBPessoa WHERE gestor = 1
        $sql1 = "SELECT distinct IDPessoa, nomeCompleto
                 FROM TBPessoa
                 WHERE gestor = 1";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        $gerentes = array();
        while ($row1 = $result1->fetch_assoc()) {
            $IDPessoa = $row1["IDPessoa"];
            $gerente = $row1["nomeCompleto"];
            $gerentes[$IDPessoa] = $gerente;
        }
        
        $resultArray['gerentes'] = $gerentes;
        // Consulta 2: SELECT * FROM TBSecao
        $sql2 = "SELECT * FROM TBSecao";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $secoes = array();
        while ($row2 = $result2->fetch_assoc()) {
            $IDSecao = $row2["IDSecao"];
            $descricaoSecao = $row2["descricao_secao"];
            $secoes[$IDSecao] = $descricaoSecao;
        }
        $resultArray['secoes'] = $secoes;

        $stmt1->close();
        $stmt2->close();
        $conn->close();

        // Retornar o resultado como JSON
        echo json_encode($resultArray);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['nomeSecao']) && isset($_POST['gerente']) && isset($_POST['setor'])){
        $nomeSecao = $_POST['nomeSecao'];
        $gerente = $_POST['gerente'];
        $setor = $_POST['setor'];

        $sql = "SET @ultimoIDSecao = (SELECT MAX(IDSecao) FROM tbsecao);
        SET @ultimoIDSecao = IFNULL(@ultimoIDSecao, 0) + 1;
        INSERT INTO tbsecao (IDSecao, descricao_secao, gerente, setor)
        VALUES (@ultimoIDSecao, '$nomeSecao', '$gerente', '$setor');";        
        if (mysqli_multi_query($conn, $sql)) {
            echo "Nova seção adicionada com sucesso!";
        } else {
            echo "Erro ao adicionar nova seção: " . mysqli_error($conn);
        }

        $conn->close();
    }
}

?>