<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST["select_setor"])){
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }
    
        $sql = "SELECT * FROM tbsetor_secao";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $id = $row["ID"];
                $setor = $row["descricao"];

                echo "<option value='".$id."'>".$setor."</option>";
            }
        }
        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function inserirUsuario($conn, $username, $cpf) {
        $sql = "SELECT MAX(IDPessoa) FROM TBPessoa";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);
        $ultimoIDPessoa = $row[0] ?? 0;


        $hashed_password = password_hash($cpf, PASSWORD_DEFAULT);


        $sql = "INSERT INTO TBUsuario (IDUsuario, nome, senha, administrador, habilitado) VALUES (?, ?, ?, 0, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $ultimoIDPessoa, $username, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    if (isset($_POST['insertUser'])) {
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $matricula = $_POST['matricula'];
        $setor_secao = $_POST['setor'];
        $email = $_POST['email'];
        $insertUser = $_POST['insertUser'];
        
        if (isset($nome) && isset($cpf) && isset($matricula) && isset($setor_secao) && isset($email)) {
            $sql = "INSERT INTO TBPessoa (nomeCompleto, cpf, matricula, setor_secao, email, gestor) VALUES (
                '$nome',
                '$cpf',
                '$matricula',
                '$setor_secao',
                '$email',
                ''
                
            )";
            echo $sql;

            if (mysqli_query($conn, $sql)) {
                // echo "Nova pessoa adicionada com sucesso!";
                if ($insertUser == 1) {
                    
                    $username = $_POST['username'];
                    inserirUsuario($conn, $username, $cpf);
                }
            } else {
                // echo "Erro ao adicionar nova pessoa: motivo->" . mysqli_error($conn);
            }

            $conn->close();
        }
    }
}
    ?>