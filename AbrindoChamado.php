<?php
//aparentemente está tudo certo com a inserção da imagem. amanha tentar exibir as imagens na DetalhandoChamado
session_start();
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $conn = mysqli_connect("localhost", "root", "", "siasb");

    if (isset($_POST['assunto']) && isset($_POST['descricao']) && isset($_POST['equipamento']) && isset($_POST['categoria'])) {
        // var_dump($_POST);
        $assunto = $_POST['assunto'];
        $descricao = $_POST['descricao'];
        $equipamento = $_POST['equipamento'];
        $categoria = $_POST['categoria'];
        $username = $_SESSION['username'];
        $existeEquipamento = $_POST['existeEquipamento'];
        $equipamentoNome = $_POST['equipamentoNome'];

        $getUserID = "SELECT IDUsuario FROM TBusuario WHERE nome = '$username'";
        $result = mysqli_query($conn, $getUserID);
        $row = mysqli_fetch_assoc($result);
        $userID = $row['IDUsuario'];

        if ($existeEquipamento == 'true') {
            $sql1 = "INSERT INTO TBequipamentos(sti_id, descricao, tipo, usuario) VALUES ($equipamento, '" . $equipamentoNome . "', 1, $userID);";
            $result1 = mysqli_query($conn, $sql1);

            if ($result1) {
                echo "Equipamento adicionado com sucesso";
                echo "<br>";
                echo "\n";
            } else {
                echo "Erro ao adicionar equipamento: " . mysqli_error($conn);
            }
        }

        $datetime = date('Y-m-d H:i:s');
        //verifica se tem imagem:
        if (isset($_FILES['imagem'])) {
            // Recebendo a imagem
            $imagem = $_FILES['imagem']['tmp_name'];
            $imagem_nome = $_FILES['imagem']['name'];
            $imagem_tipo = $_FILES['imagem']['type'];

            // Convertendo a imagem para dados binários (blob)
            $imagem_blob = addslashes(file_get_contents($imagem));
        } else {
            $imagem_blob = null;
        }

        $sql = "
        SET @UltimoID = (SELECT MAX(IDChamado) FROM tbchamados);
        SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
        INSERT INTO tbchamados (IDChamado, assunto, descricao, dataAbertura, status_chamado, responsavel, autor, equipamento, imagem, categoria)
        VALUES (@UltimoID, '$assunto', '$descricao', '$datetime', 1, null, '$userID', $equipamento, '$imagem_blob', '$categoria');
        ";
        $result = mysqli_multi_query($conn, $sql);

        if ($result) {
            echo "Novo chamado adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar chamado: " . mysqli_error($conn);
            echo "<br>";
            echo "\n";
            echo $sql;

        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['stiID'])) {
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $stiID = $_POST['stiID'];
        $descricao = '';

        $sql = "SELECT descricao from tbequipamentos WHERE sti_id = $stiID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $descricao = $row['descricao'];
            } else
                $descricao = 'Equipamento nao encontrado';
        } else {
            echo "Digite um STI_ID valido (números inteiros)";
        }
        echo $descricao;
    }
}
?>