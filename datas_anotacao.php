<?php
// Configuração de conexão com o banco de dados (substitua pelos seus dados)
$host = 'localhost';          // Por exemplo: 'localhost' ou o endereço do seu servidor de banco de dados
$dbname = 'siasb';  // O nome do seu banco de dados
$username = 'root';  // O nome de usuário do seu banco de dados
$password = '';    // A senha do seu banco de dados

try {
    // Conectar ao banco de dados usando MySQLi
    $conn = new mysqli($host, $username, $password, $dbname);

    // Verificar se houve algum erro na conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Executar a consulta SQL para buscar as datas especiais
    $sql = "SELECT dataAbertura FROM tbchamados";
    $result = $conn->query($sql);

    // Montar um array com as datas especiais
    $dates = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dates[] = $row["dataAbertura"];
        }
    }

    // Fechar a conexão com o banco de dados
    $conn->close();

    // Retornar os resultados como JSON
    header('Content-Type: application/json');
    echo json_encode($dates);
} catch (Exception $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}
?>
