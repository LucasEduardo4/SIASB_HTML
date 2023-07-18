<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Informações do Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
        }

        #user-info {
            background-color: #fff;
            margin: 20px;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #user-info p {
            margin: 10px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div id="user-info">
        <?php
        // Conexão com o banco de dados
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "siasb";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Falha na conexão com o banco de dados: " . $conn->connect_error);
        }



        //================================================================
        $username = $_SESSION['username'];

        $sql = "SELECT * FROM tbusuario WHERE nome = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Encontrou resultados
            $row = $result->fetch_assoc();
            $Meu_ID = $row["IDUsuario"];
        }

        // Consulta para recuperar as informações do usuário
        $sql = "SELECT IDPessoa, nomeCompleto, cpf, matricula, setor, secao, email FROM tbpessoa WHERE IDPessoa = $Meu_ID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<h2>Informações do Usuário</h2>";
            echo "<p><strong>ID:</strong> " . $row['IDPessoa'] . "</p>";
            echo "<p><strong>Nome Completo:</strong> " . $row['nomeCompleto'] . "</p>";
            echo "<p><strong>CPF:</strong> " . $row['cpf'] . "</p>";
            echo "<p><strong>Matrícula:</strong> " . $row['matricula'] . "</p>";
            echo "<p><strong>Setor:</strong> " . $row['setor'] . "</p>";
            echo "<p><strong>Seção:</strong> " . $row['secao'] . "</p>";
            echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
        } else {
            echo "Nenhum usuário encontrado.";
        }
        $conn->close();
        ?>
    </div>

    <!-- //================================================================ -->

    <!-- REALIZANDO A ALTERAÇÃO DA SENHA -->

    <h2>Alteração de Senha</h2>

    <form method="POST">

    <label for="nova_senha">Nova Senha:</label>
    <input type="password" id="nova_senha" name="nova_senha" required><br><br>

    <input type="submit" value="Enviar">
    </form>

    <!-- PHP REALIZANDO A ALTERAÇÃO DA SENHA -->

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "siasb";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Dados desejados para atualização
    $usuario_ = $_SESSION['username'];
    $nova_senha = $_POST['nova_senha'];

    // Verificar se o usuário existe
    $sql = "SELECT * FROM tbusuario WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario_);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Atualizar a senha
        $hashed_password = password_hash($nova_senha, PASSWORD_DEFAULT);

        $sql = "UPDATE tbusuario SET senha = ? WHERE nome = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $usuario_);
        if ($stmt->execute()) {
            echo "Senha atualizada com sucesso!";
        } else {
            echo "Erro ao atualizar a senha: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Usuário não encontrado.";
    }

    $conn->close();
} 
?>


</body>
</html>


