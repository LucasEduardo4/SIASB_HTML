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

                echo "<option value='".$setor."'>".$setor."</option>";
            }
        }
        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $matricula = $_POST['matricula'];
    $setor = $_POST['setor'];
    $email = $_POST['email'];

    // echo "Valores recebidos: " . $nome . " " . $cpf . " " . $matricula . " " . $setor . " " . $email;
    if(isset($nome) && isset($cpf) && isset($matricula) && isset($setor) && isset($email)){
        $sql = "
            START TRANSACTION;
            SET @UltimoIDPessoa = (SELECT MAX(IDPessoa) FROM TBPessoa);
            SET @UltimoIDPessoa = IFNULL(@UltimoIDPessoa, 0) + 1;
            INSERT INTO TBPessoa VALUES(
            '@IDPessoa',
            '$nome',
            '$cpf',
            '$matricula',
            '$setor',
            '1',
            '$email'
            );
            COMMIT;
            ";
            
            if (mysqli_multi_query($conn, $sql)) {
                echo "Novo status adicionado com sucesso!";
            } else {
                echo "Erro ao adicionar novo status: " . mysqli_error($conn);
            }
    
            $conn->close();
    }
}	
?>