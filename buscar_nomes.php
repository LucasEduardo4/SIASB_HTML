<?php
require_once("model/conexao.php");

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$termo = $_GET["termo"];

$query = "SELECT nomeCompleto FROM tbpessoa WHERE nomeCompleto LIKE '%$termo%'";
$result = $conn->query($query);

$nomes = array();

while ($row = $result->fetch_assoc()) {
    $nomes[] = $row["nomeCompleto"];
}

echo json_encode($nomes);

$conn->close();
?>
