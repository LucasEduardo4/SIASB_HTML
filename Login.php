<?php
// Verifique se os campos foram preenchidos
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $host = 'localhost';
  $db = 'siasb';
  $user = 'root';
  $pass = '';

  $conn = new mysqli($host, $user, $pass, $db);
  if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
  }


  //PEGANDO UM DETERMINADO DADO DO BANCO DE DADOS (ÓTIMO PARA REALIZAR QUANDO ESTAMOS TRABALHANDO COM UM USUÁRIO CONECTADO NO SISTEMA)
  $sql = "SELECT * FROM tbusuario WHERE nome = ? AND habilitado = 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $login_habilitado = $row["habilitado"];

    if ($login_habilitado == "1") {
      $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

      try {
        $pdo = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
        echo 'Erro de conexão: ' . $e->getMessage();
        exit;
      }

      $stmt = $pdo->prepare('SELECT senha FROM tbusuario WHERE nome = ? LIMIT 1');
      $stmt->execute([$username]);
      $row = $stmt->fetch();
      if ($row) {
        $storedPassword = $row['senha'];

        if (password_verify($password, $storedPassword)) {
          session_start();
          $_SESSION['username'] = $_POST['username'];
          header('Location: sidebars/index.php');
          exit;
        } else {
          header('Location: Login.html?error=error');
        }

      } else {
        header('Location: Login.html?error=true');
      }

    } else {
      header('Location: Login.html?error=error');
    }

    $stmt->closeCursor();
    $conn->close();
  } else {
    header('Location: Login.html?error=error');
  }

}
?>