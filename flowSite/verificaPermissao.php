<?php
session_start();

if (!isset($_SESSION['username'])) {
    // header("Location: ../login.html");
    echo "noSession";

    exit();
} else
    if (isset($_POST['verificaPermissao']) || isset($_POST['verificaAtivo'])) {
        $conn = mysqli_connect('localhost', 'root', '', 'siasb');
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
                $administrador = $row["administrador"];

                if (isset($_POST['verificaPermissao'])) {
                    if ($administrador == 1 && $habilitado == 1) {
                        echo 'permitido! adm e habilitado é 1';
                    } elseif ($habilitado != 1) {
                        echo 'desabilitado';
                    } elseif ($administrador != 1) {
                        echo 'sem permissao';
                    } elseif ($habilitado == 1) {
                        echo 'habilitado';
                    }
                }
            }
        }
    }
?>