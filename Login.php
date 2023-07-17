<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="post">
        <div>
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>
        <div>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <div>
            <input type="submit" value="Entrar">
        </div>
    </form>
</body>
</html>




<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siasb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Dado desejado para busca
$login_usuario = $_POST['username'];
$login_senha = $_POST['password'];

// Consulta SQL
$sql = "SELECT * FROM tbusuario WHERE nome = ? AND senha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $login_usuario, $login_senha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Encontrou resultados
    $row = $result->fetch_assoc();
    echo "Dado encontrado!<br>";
    echo "Senha: " . $row["senha"] . "<br>";
    echo "Nome: " . $row["nome"] . "<br>";
    $login_habilitado = $row["habilitado"];

    if ($login_habilitado == "1") 
    {           
        session_start();
        $_SESSION['username'] = $_POST['username'];
        header('Location: sidebars/index.html');
        echo "Usuário pode realizar o login!";
    
    }else {
        // header('Location: Login.html?error=true');
        header('Location: Login.html?error=true');
    }

    // Exibir outros campos conforme necessário
} else {
    header('Location: Login.html?error=true');
}

$stmt->close();
$conn->close();
?>