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
?>
