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
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sidebars/bootstrap.min.css">
    <link rel="icon" href="icons/warning.png" sizes="192x192"/>


    <title>Confirmação</title>
</head>
<body>
<div class="container my-5">
        
        <h1>Valores Recebidos:</h1>
        <hr>
        <p>Nome = <?php echo $nome; ?></p>
        <p>CPF = <?php echo $cpf; ?></p>
        <p>Matrícula = <?php echo $matricula; ?></p>
        <p>Setor = <?php echo $setor; ?></p>
        <p>Email = <?php echo $email; ?></p>
        <p>Os dados estão corretos?</p>
        <button type="button" class="btn btn-success" onclick="insert()">Confirmar</button>
        <button type="button" class="btn btn-danger" onclick = "previous()">Retornar</button>
    </div>
    <script>
       function previous(){
        history.back();
       }
       function insert(){
        <?php
          $sql = "INSERT INTO TBPessoa (nome, cpf, matricula, setor, secao, email) VALUES ('$nome', '$cpf', '$matricula', '$setor', 'NULL','$email')";
          $result = mysqli_query($conn, $sql);

          $conn->close();

          ?>
       }
    </script>
</body>
</html>