<?php
// Conexão com o banco de dados (substitua os valores conforme a sua configuração)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siasb";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"]) && isset($_POST["novo_conteudo"])) {
  $id = $_POST["id"];
  $novo_conteudo = $_POST["novo_conteudo"];

  // Atualize o conteúdo da anotação no banco de dados
  $sql = "UPDATE tbagenda SET mensagem = ? WHERE IDAgenda = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $novo_conteudo, $id);

  if ($stmt->execute()) {
      // Resposta de sucesso
      echo "Anotação atualizada com sucesso!";
  } else {
      // Resposta de erro
      echo "Erro ao atualizar anotação: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}


?>