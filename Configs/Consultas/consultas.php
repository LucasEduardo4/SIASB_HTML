<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["select_ready"])) {
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
            $icone = "<i class='bi bi-check-square' onclick='onoffGerente(" . $IDPessoa . ")' value='" . $gerente . "' id='sqr-" . $IDPessoa . "'></i>";
        } else {
            // Usar ícone padrão
            $icone = "<i class='bi bi-square' onclick='onoffGerente(" . $IDPessoa . ")' value='" . $gerente . "' id='sqr-" . $IDPessoa	 . "'></i>";
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
?>
