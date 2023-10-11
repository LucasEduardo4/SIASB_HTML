<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["select_setor"])) {
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $sql = "SELECT * FROM tbsetor_secao";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row["ID"];
                $setor = $row["descricao"];

                echo "<option value='" . $id . "'>" . $setor . "</option>";
            }
        }
        $stmt->close();
        $conn->close();
    }
    function inserirUsuario($conn, $username, $cpf)
    {
        $sql = "SELECT MAX(IDPessoa) FROM TBPessoa";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);
        $ultimoIDPessoa = $row[0] ?? 0;


        $hashed_password = password_hash($cpf, PASSWORD_DEFAULT);


        $sql = "INSERT INTO TBUsuario (IDUsuario, nome, senha, administrador, habilitado) VALUES (?, ?, ?, 0, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $ultimoIDPessoa, $username, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    if (isset($_POST['insertUser'])) {
        var_dump($_POST);
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $matricula = $_POST['matricula'];
        $setor_secao = $_POST['setor'];
        $email = $_POST['email'];
        $insertUser = $_POST['insertUser'];
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);

        if (isset($_FILES['imagem'])) {
            // Recebendo a imagem
            $imagem = $_FILES['imagem']['tmp_name'];
            $imagem_nome = $_FILES['imagem']['name'];
            $imagem_tipo = $_FILES['imagem']['type'];

            $imagem_blob = addslashes(file_get_contents($imagem));
        } else {
            $imagem_blob = NULL;
        }

        if (isset($nome) && isset($cpf) && isset($matricula) && isset($setor_secao) && isset($email)) {
            $sql = "INSERT INTO TBPessoa (nomeCompleto, cpf, matricula, setor_secao, email, icone) VALUES (
                '$nome',
                '$cpf',
                '$matricula',
                '$setor_secao',
                '$email',
                '$imagem_blob'
            )";
            // echo $sql;

            if (mysqli_query($conn, $sql)) {
                if ($insertUser == 1) {
                    $username = $_POST['username'];
                    inserirUsuario($conn, $username, $cpf);
                }
            } else {
                // echo "Erro ao adicionar nova pessoa: motivo->" . mysqli_error($conn);
            }

            $conn->close();
        }

    } else
        if (isset($_POST['verify_usernames'])) {
            $username1 = $_POST['username1'];
            if (isset($_POST['username2']))
                $username2 = $_POST['username2'];
            else
                $username2 = "";

            $sql = "SELECT * FROM TBUsuario WHERE nome = '$username1'";
            $result = mysqli_query($conn, $sql);
            $result->fetch_assoc();
            if ($result->num_rows > 0) {
                $username1 = $username1 . "1";
            }

            $sql2 = "SELECT * FROM TBUsuario WHERE nome = '$username2'";
            $result2 = mysqli_query($conn, $sql2);
            $result2->fetch_assoc();
            if ($result2->num_rows > 0) {
                if ($username2 != "")
                    $username2 = $username2 . "1";
            }

            $result = array();
            $result[0] = $username1;
            $result[1] = $username2;

            echo json_encode($result);

        } else if (isset($_POST['check_name'])) {
            $username = $_POST['username'];
            $sql = "SELECT * FROM TBUsuario WHERE nome = '$username'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
            if ($result->num_rows > 0) {
                echo "error";
            } else {
                echo "$username";
            }


        }
}
?>