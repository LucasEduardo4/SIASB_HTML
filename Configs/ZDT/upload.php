<?php

// Conecte-se ao banco de dados
$conn = new mysqli("localhost", "root", "", "siasb");

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
if (!move_uploaded_file($imagem['tmp_name'], $uploadFile)) {
    echo json_encode(['success' => false, 'message' => 'Erro ao fazer o upload da imagem.']);
    exit;
}

// Continuar com a conexão ao banco de dados e a inserção do caminho da imagem no banco
// ...

// Exemplo de como inserir o caminho da imagem no banco de dados
$sql = "INSERT INTO tbchamados (imagem) VALUES ('$uploadFile')";

$id_chamado = 2; // Insira aqui o ID do chamado que você deseja associar a imagem

// Atualiza a coluna "imagem" na tabela "tbchamados" com o conteúdo codificado da imagem
$resultado = $conn->query("UPDATE tbchamados SET imagem = '$conteudo_imagem_codificado' WHERE idchamado = $id_chamado");

if ($resultado) {
    echo 'A imagem foi inserida com sucesso no banco de dados.';
} else {
    echo 'A inserção da imagem falhou: ' . $conn->error;
}

// Desconecte-se do banco de dados
$conn->close();

?>
