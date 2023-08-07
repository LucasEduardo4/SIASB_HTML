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

// Verificar se o ID da anotação foi recebido na requisição POST
if (isset($_POST["id"])) {
  $anotacaoId = $_POST["id"];

  

  // Montar a consulta SQL para excluir a anotação do banco de dados
  $sql = "DELETE FROM tbagenda WHERE IDAgenda = $anotacaoId";
  echo $anotacaoId;

  //   echo "venha conferir";
  

  // Executar a consulta
  if ($conn->query($sql) === TRUE) {
    // Responder com sucesso se a exclusão foi realizada com sucesso
    
    echo "Anotação excluída com sucesso!";
  } else {
    // Responder com erro se a exclusão falhou
    echo "Erro ao excluir a anotação: " . $conn->error;
  }
}

// Fechar a conexão com o banco de dados
$conn->close();

// Verifique se há uma solicitação POST com o ID da anotação e o novo conteúdo
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

