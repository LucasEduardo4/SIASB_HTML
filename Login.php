<?php
    if(isset ($_POST['username']) && isset($_POST['password'])){
        if($_POST['username'] == 'user' && $_POST['password'] == 'toor'){
            session_start();
            $_SESSION['username'] = $_POST['username'];
            header('Location: sidebars/index.html');
        }else{
            header('Location: Login.html?error=true');
        }

    }

?>


<?php
// Conecte-se ao banco de dados MySQL (substitua com suas credenciais)

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Processa o formulário de login quando for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta o banco de dados para verificar se o usuário existe
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verifica se a senha fornecida corresponde à senha armazenada no banco de dados
        if (password_verify($password, $user['password'])) {
            // Autenticação bem-sucedida
            session_start();
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); // Redireciona para a página do painel de controle
        } else {
            // Senha incorreta
            $error = "Senha incorreta";
        }
    } else {
        // Usuário não encontrado
        $error = "Usuário não encontrado";
    }
}
?>



