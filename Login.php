

<?php
// Verifique se os campos foram preenchidos
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Faça a conexão com o banco de dados (substitua as informações de conexão com as suas)
  $host = 'localhost';
  $db   = 'siasb';
  $user = 'root';
  $pass = '';

  $conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}


//PEGANDO UM DETERMINADO DADO DO BANCO DE DADOS (ÓTIMO PARA REALIZAR QUANDO ESTAMOS TRABALHANDO COM UM USUÁRIO CONECTADO NO SISTEMA)
$sql = "SELECT * FROM tbusuario WHERE nome = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
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
      $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

      try {
        $pdo = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
        echo 'Erro de conexão: ' . $e->getMessage();
        exit;
      }
    
      // Consulta para obter a senha hash correspondente ao nome de usuário fornecido
      $stmt = $pdo->prepare('SELECT senha FROM tbusuario WHERE nome = ? LIMIT 1');
      $stmt->execute([$username]);
      $row = $stmt->fetch();
    
      // Verifique se a consulta retornou um resultado
      if ($row) {
        $storedPassword = $row['senha'];
    
        // Verifique se a senha inserida corresponde à senha armazenada (você pode usar a função de hash adequada)
        if (password_verify($password, $storedPassword)) {
          // Senha correta, redirecione para a página protegida
          session_start();
          $_SESSION['username'] = $_POST['username'];
          header('Location: sidebars/index.html');
          exit;
        }
      }
    
      // Nome de usuário ou senha incorretos
      header('Location: Login.html?error=true');
    
    }else {
        // header('Location: Login.html?error=true');
        header('Location: Login.html?error=true');
    }

    // Exibir outros campos conforme necessário
} else {
    header('Location: Login.html?error=true');
}

$stmt->close();
$conn->close();


}
?>
