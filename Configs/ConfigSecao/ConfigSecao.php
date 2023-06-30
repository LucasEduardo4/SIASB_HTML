<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

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

        // Consulta 2: SELECT * FROM TBSetor
        $sql2 = "SELECT * FROM TBSetor";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $setores = array();
        while ($row2 = $result2->fetch_assoc()) {
            $IDSetor = $row2["IDSetor"];
            $descricaoSetor = $row2["descricao_setor"];
            $setores[$IDSetor] = $descricaoSetor;
        }
        $resultArray['setores'] = $setores;

        $stmt1->close();
        $stmt2->close();
        $conn->close();

        // Retornar o resultado como JSON
        echo json_encode($resultArray);
    }
}

?>