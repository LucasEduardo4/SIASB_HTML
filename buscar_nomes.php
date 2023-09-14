<?php
$mysqli = new mysqli("localhost", "root", "", "siasb");

if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

$termo = $_GET["termo"];

$query = "SELECT nomeCompleto FROM tbpessoa WHERE nomeCompleto LIKE '%$termo%'";
$result = $mysqli->query($query);

$nomes = array();

while ($row = $result->fetch_assoc()) {
    $nomes[] = $row["nomeCompleto"];
}

echo json_encode($nomes);

$mysqli->close();
?>
