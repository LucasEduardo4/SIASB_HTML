<?php
// Configurar a conexão com o banco de dados (substitua pelos seus dados)
$host = 'localhost';
$dbname = 'siasb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Executar a consulta SQL para buscar as datas especiais
    $stmt = $conn->query("SELECT dataAbertura FROM tbchamados");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar os dados como JSON
    header('Content-Type: application/json');
    echo json_encode($result);
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}
