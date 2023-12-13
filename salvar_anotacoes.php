<?php
// Obter os dados enviados pela requisição AJAX
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    session_start();
    $date = $_POST['date'];
    $message = $_POST['message'];
    
    require_once("model/conexao.php");

    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

$usuario_ = $_SESSION['username'];

// Verificar se o usuário existe
// Preparar e executar a consulta SQL para inserir os dados na tabela
$sql = "SET @ultimoID = (SELECT MAX(IDAgenda) FROM tbagenda);
        SET @ultimoID = IFNULL(@ultimoID, 0) + 1;
        SET @IDUsuario = (SELECT IDUsuario FROM tbusuario WHERE nome = '$usuario_');
        INSERT INTO tbagenda (IDAgenda, mensagem, dia, IDUsuario) VALUES (@ultimoID, '$message', '$date', @IDUsuario)";
if (mysqli_multi_query($conn, $sql)) {
    echo "Nova seção adicionada com sucesso!";
} else {
    echo "Erro ao adicionar nova seção: " . mysqli_error($conn);
}




// Fechar a conexão com o banco de dados
$conn->close();
}
?>
