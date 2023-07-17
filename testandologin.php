<!DOCTYPE html>
<html>
<head>
  <title>Alteração de Senha</title>
</head>
<body>
  <h2>Alteração de Senha</h2>

  <form action="alterar_senha.php" method="POST">

    <label for="nova_senha">Nova Senha:</label>
    <input type="password" id="nova_senha" name="nova_senha" required><br><br>

    <input type="submit" value="Alterar Senha">
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
$senha_ = $_SESSION['password'];

echo $usuario_ ;

// Consulta SQL
$sql = "SELECT * FROM tbusuario WHERE nome = ? AND senha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $login_usuario, $login_senha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Encontrou resultados
    $row = $result->fetch_assoc();
    echo "Dado encontrado!<br>";
    echo "Senha: " . $row["senha"] . "<br>";
    echo "Nome: " . $row["nome"] . "<br>";
    $login_habilitado = $row["habilitado"];

    if ($login_habilitado == "1") 
    {           
        session_start();
        $_SESSION['username'] = $_POST['username'];
        header('Location: sidebars/index.html');
        echo "Usuário pode realizar o login!";
    
    }
} 

$stmt->close();
$conn->close();
?>




<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siasb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Recebendo os dados do formulário
$usuario_senha = $_SESSION['username'];
// $senha_atual = $_POST['senha_atual'];
$nova_senha = $_POST['nova_senha'];

// Verificar a senha atual do usuário no banco de dados (você precisa adaptar essa consulta)
$sql = "SELECT senha FROM tbusuario WHERE nome = $usuario_senha";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $senha_armazenada = $row['senha'];

  // Comparar a senha atual fornecida pelo usuário com a senha armazenada no banco de dados
  if ($senha_atual == $senha_armazenada) {
    // Atualizar a nova senha no banco de dados (você precisa adaptar essa consulta)
    $sql = "UPDATE tbusuario SET senha = '$nova_senha' WHERE nome = $usuario_senha";

    if ($conn->query($sql) === TRUE) {
      echo "Senha atualizada com sucesso!";
    } else {
      echo "Erro ao atualizar a senha: " . $conn->error;
    }
  } else {
    echo "Senha atual incorreta.";
  }
} else {
  echo "Usuário não encontrado.";
}

$conn->close();
?>
