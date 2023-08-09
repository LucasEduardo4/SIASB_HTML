
<?php
// Conexão com o banco de dados (substitua com suas próprias informações)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siasb";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para buscar as datas especiais na coluna 'dataAbertura' da tabela 'tbchamados'
$sql = "SELECT dataAbertura FROM tbchamados"; // Substitua com sua consulta SQL

$result = $conn->query($sql);

$specialDate = array();

if ($result->num_rows > 0) {
    // Loop através dos resultados e adicionar as datas ao array
    while($row = $result->fetch_assoc()) {
        $specialDate[] = $row["dataAbertura"];
    }
} else {
    // Caso não haja resultados
    echo "Nenhuma data especial encontrada.";
}

// Fechar conexão com o banco de dados
$conn->close();

// Retornar as datas em formato JSON
echo json_encode($specialDate);
?>
