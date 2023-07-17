<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST["select_setor"])){
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }
    
        $sql = "SELECT * FROM TBSetor";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $setor = $row["descricao_setor"];
                $id = $row["IDSetor"];

                echo "<option value='".$id."'>".$setor."</option>";
            }
        }
        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function inserirUsuario($conn, $username) {
        echo "entrou na função de inserir";
        $sql = "SELECT MAX(IDPessoa) FROM TBPessoa";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);
        $ultimoIDPessoa = $row[0] ?? 0;
        $ultimoIDPessoa++;

        $sql = "INSERT INTO TBUsuario (IDUsuario, nome, senha, administrador, habilitado) VALUES (?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $ultimoIDPessoa, $username, $senha, $adm);
        $stmt->execute();
        $result = $stmt->get_result();
        echo "\nUsuario inserido com sucesso -> " . $result;
        echo "\nSQL Line -> " . $sql;
    }

    if (isset($_POST['insertUser'])) {
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $matricula = $_POST['matricula'];
        $setor = $_POST['setor'];
        $email = $_POST['email'];
        $insertUser = $_POST['insertUser'];
        $username = $_POST['username'];

        if (isset($nome) && isset($cpf) && isset($matricula) && isset($setor) && isset($email)) {
            $sql = "INSERT INTO TBPessoa (IDPessoa, nomeCompleto, cpf, matricula, setor, secao, email, gestor) VALUES (
                NULL,
                '$nome',
                '$cpf',
                '$matricula',
                '$setor',
                1,
                '$email',
                ''
            )";

            if (mysqli_query($conn, $sql)) {
                echo "Nova pessoa adicionada com sucesso!";
                if ($insertUser == 1) {
                    inserirUsuario($conn, $username);
                }
            } else {
                echo "Erro ao adicionar nova pessoa: motivo->" . mysqli_error($conn);
            }

            $conn->close();
        }
    }
}

    ?>