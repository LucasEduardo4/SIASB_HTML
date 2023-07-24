<?php
// Verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver autenticado
    exit();
}
?>



<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
    $imagem = $_FILES['imagem'];

    // Verificar se é uma imagem válida
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExtension = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Formato de imagem não suportado.']);
        exit;
    }

    // Salvar a imagem em algum diretório no servidor
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($imagem['name']);
    if (move_uploaded_file($imagem['tmp_name'], $uploadFile)) {
        // Conexão com o banco de dados (substitua pelas suas informações)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "siasb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexão
        if ($conn->connect_error) {
            die("Conexão com o banco de dados falhou: " . $conn->connect_error);
        }

        // Verificar se o registro do usuário já existe na tabela
        // Supondo que você tenha uma variável com o ID do usuário
        $userID = 1; // Substitua pelo ID do usuário que deseja atualizar

        $sqlCheckUser = "SELECT IDUsuario FROM tbusuario WHERE IDUsuario = $userID";
        $resultCheckUser = $conn->query($sqlCheckUser);

        if ($resultCheckUser->num_rows > 0) {
            // Registro do usuário já existe, então vamos atualizar o caminho da imagem (coluna "icone")
            $imageUrl = $uploadFile;
            $sqlUpdate = "UPDATE tbusuario SET icone = '$imageUrl' WHERE IDUsuario = $userID";

            if ($conn->query($sqlUpdate) === TRUE) {
                echo json_encode(['success' => true, 'imageUrl' => $imageUrl]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o banco de dados: ' . $conn->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não encontrado na tabela tbusuario.']);
        }

        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao fazer o upload da imagem.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}
?>
