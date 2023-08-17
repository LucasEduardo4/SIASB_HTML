<?php
// Verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redireciona para a página de login se não estiver autenticado
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

    // Read image data as binary
    $imageData = file_get_contents($imagem['tmp_name']);

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

    // =================================================== REALIZANDO TESTES =================================================
    $usuario_ = $_SESSION['username'];
    $sql = "SELECT * FROM tbusuario WHERE nome = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario_);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Encontrou resultados
        $row = $result->fetch_assoc();
        $Meu_ID = $row["IDUsuario"];
    }
    // =================================================== REALIZANDO TESTES =================================================

    // Verificar se o registro do usuário já existe na tabela
    $userID = $Meu_ID;
    
    $sqlCheckUser = "SELECT IDUsuario FROM tbusuario WHERE IDUsuario = ?";
    $stmtCheckUser = $conn->prepare($sqlCheckUser);
    $stmtCheckUser->bind_param("i", $userID);
    $stmtCheckUser->execute();
    $resultCheckUser = $stmtCheckUser->get_result();

    if ($resultCheckUser->num_rows > 0) {
        // Registro do usuário já existe, então vamos atualizar a imagem (coluna "icone")
        $sqlUpdate = "UPDATE tbusuario SET icone = ? WHERE IDUsuario = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("si", $imageData, $userID);
        
        if ($stmtUpdate->execute()) {
            echo json_encode(['success' => true, 'message' => 'Imagem atualizada com sucesso.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o banco de dados: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID do usuário não encontrado na tabela tbusuario.']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}
?>
