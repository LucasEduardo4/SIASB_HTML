<?php
// Verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver autenticado
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Alteração de Senha</title>
</head>
<body>
  <h2>Alteração de Senha</h2>

  <form  method="POST">

    <label for="nova_senha">Nova Senha:</label>
    <input type="password" id="nova_senha" name="nova_senha" required><br><br>

    <input type="submit">
  </form>
</body>
</html>



<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siasb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Dado desejado para busca
$usuario_ = $_SESSION['username'];
$senha_ = $_POST ['nova_senha'];


// Consulta SQL
$sql = "SELECT * FROM tbusuario WHERE nome = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    // Encontrou resultados
    $row = $result->fetch_assoc();
    echo "FUNCIONANDO!<br>";
    echo "Senha: " . $row["senha"] . "<br>";
    echo "Nome: " . $row["nome"] . "<br>";
    $senha_ = $row["senha"];

    $sql = "UPDATE tbusuario SET senha = '$senha_' WHERE nome = '$usuario_'";

    if ($conn->query($sql) === TRUE) {
      echo "Senha atualizada com sucesso!";
    } else {
      echo "Erro ao atualizar a senha: " . $conn->error;
    }


} 

$stmt->close();
$conn->close();
?>




