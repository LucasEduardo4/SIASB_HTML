<?php
require_once("model/conexao.php");

session_start();
$_SESSION['even'] = 0;

if (!isset($_SESSION['username'])) {
    $_SESSION['even'] = $_SESSION['even'] + 1;
    if ($_SESSION['even'] / 2 == 0) {
        header("Location: ../login.html");
        exit();
    } else
        echo "<script>window.parent.location.reload();</script>";

    //abaixo está a verificação se o usuário está ativo:
    $username = $_SESSION['username'];

    $sql = "SELECT * FROM TBUsuario u  
    LEFT JOIN TBPessoa p on p.IDPessoa = u.IDUsuario
    WHERE u.nome = '$username'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $habilitado = $row["habilitado"];
            $setor_secao = $row["setor_secao"];

            if ($habilitado != 1) {
                header("Location: flowsite/usuarioinativo.html");
            }

        }
    }
    exit();

}

$username = $_SESSION['username'];

$sql = "SELECT * FROM TBUsuario u  
        LEFT JOIN TBPessoa p on p.IDPessoa = u.IDUsuario
        WHERE u.nome = '$username'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $habilitado = $row["habilitado"];
        $setor_secao = $row["setor_secao"];

        // $resultHabilitado = $habilitado == 1 ? true : false;
        // $resultSetorSecao = $setor_secao == 1 ? true : false;

        // if($resultHabilitado && $resultSetorSecao){
        //     echo 'true';
        // }else{
        //     echo 'false';
        // }

        if ($habilitado != 1) {
            header("Location: flowsite/usuarioinativo.html");
        } else {
            // echo 'permitido!';
        }
    }
}
?>

<?php

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


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

// $sql = "SELECT IDPessoa, nomeCompleto, cpf, matricula, setor, secao, email FROM tbusuario  WHERE IDUsuario = $Meu_ID";

$sql = "SELECT icone FROM tbpessoa WHERE IDPessoa = $Meu_ID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imageData = $row['icone']; // Use 'icone' em vez de 'imagem_coluna'
} else {
    echo "Imagem não encontrada.";
    exit;
}


if ($imageData) {
    // Display the image using base64 encoding
    $base64Image = base64_encode($imageData);
    // echo "<img src='data:image/jpeg;base64,$base64Image' alt='User Profile Image'>";
} else {
    echo "Image not found.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Informações do Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        img {
            border-radius: 50%;
            border: solid 2px grey;
        }

        button {
            color: green;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            margin-top: 20px
        }

        .container {
            display: flex;
            /* Torna os elementos filhos em um flex container */


        }

        .div1,
        .div2 {
            flex: 1;
            /* Define a proporção de espaço que cada div irá ocupar */
            padding: 10px;
            /* Opcional: adiciona espaçamento interno às divs */
            box-sizing: border-box;
            /* Opcional: inclui o padding na largura total */
            width: 100%;
            margin-inline: auto;

        }

        .div1 {
            background-color: #f0f0f0;
            text-align: center;

        }

        .div2 {
            /* background-color: #ccc;  */
        }

        .alt_senha {
            color: green;
        }

        .texto {
            margin: auto;
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            padding-top: 30px;
            font-size: 35px;
            color: #000000;
            width: 180vh;
            flex: none;
            text-align: center;
            padding-top: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            z-index: 10;
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
            padding: 20px;
            background-color: #eeeeee;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <!-- ======================================================= -->

    <div class="texto">INFORMAÇÕES DO USUÁRIO</div>

    <div class="div1" style="width:100%">

        <div>
            <div id="anexosContainer"></div>
        </div>
        <script>

            var imagem = <?php echo json_encode(base64_encode($imageData)); ?>;

            if (imagem) {
                document.getElementById("anexosContainer").innerHTML +=
                    '<p> <img id="icone" src="data:image/jpeg;base64,' + imagem + '" width="300" height="300"  alt="" /></p>';
            } else {
                document.getElementById("anexosContainer").innerHTML +=
                    '<p>Nenhuma imagem anexada para este chamado.</p>';
            }
        </script>

        <?php
        if ($conn->connect_error) {
            die("Falha na conexão com o banco de dados: " . $conn->connect_error);
        }



        //===============================================================
        $usuario_ = $_SESSION['username'];
        // Substitua pelo ID do usuário que deseja atualizar
// $userID = $usuario_; 
        
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


        // Consulta para recuperar as informações do usuário
        $sql = "SELECT IDPessoa, nomeCompleto, cpf, matricula, setor_secao, email FROM tbpessoa WHERE IDPessoa = $Meu_ID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $row['nomeCompleto'];
        } else {
            echo "Nenhum usuário encontrado.";
        }
        ?>

    </div>

    <div class="div2" style="width:70%">
        <div id="user-info-div">
            <div id="user-info">

                <?php
                if ($conn->connect_error) {
                    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
                }

                //===============================================================
                $usuario_ = $_SESSION['username'];
                // Substitua pelo ID do usuário que deseja atualizar
                // $userID = $usuario_; 
                
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


                // Consulta para recuperar as informações do usuário
                $sql = "SELECT IDPessoa, nomeCompleto, cpf, matricula, setor_secao, email FROM tbpessoa WHERE IDPessoa = $Meu_ID";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    // echo "<h2>Informações do Usuário</h2>";
                    echo "<p><strong>Nome Completo:</strong> " . $row['nomeCompleto'] . "</p>";
                    echo "<p><strong>CPF:</strong> " . $row['cpf'] . "</p>";
                    echo "<p><strong>Matrícula:</strong> " . $row['matricula'] . "</p>";
                    echo "<p><strong>Setor / Seção:</strong> " . $row['setor_secao'] . "</p>";
                    echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                } else {
                    echo "Nenhum usuário encontrado.";
                }
                ?>
            </div>
        </div>

        <!-- REALIZANDO A ALTERAÇÃO DA SENHA -->

        <div class="div2">

            <h2 style="font-size: 15px; text-align:center">ALTERAÇÃO DE SENHA</h2>

            <form method="POST">

                <label for="nova_senha" style="font-weight: 550;">NOVA SENHA:</label>
                <input type="password" id="nova_senha" name="nova_senha" required><br><br>

                <input type="submit" value="Alterar Senha">
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
                        echo "<p class='alt_senha'>Senha atualizada com sucesso!</p>";
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
        </div>
    </div>

    </div>
</body>

</html>