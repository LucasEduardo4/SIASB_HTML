<?php
//aparentemente está tudo certo com a inserção da imagem. amanha tentar exibir as imagens na DetalhandoChamado
session_start();
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $conn = mysqli_connect("localhost", "root", "", "siasb");

    if (isset($_POST['assunto']) && isset($_POST['descricao']) && isset($_POST['equipamento']) && isset($_POST['categoria'])) {
        $assunto = $_POST['assunto'];
        $descricao = $_POST['descricao'];
        $equipamento = $_POST['equipamento'];
        $categoria = $_POST['categoria'];
        $username = $_SESSION['username'];
        $imagem = $_POST['imagem'];

        $getUserID = "SELECT IDUsuario FROM TBusuario WHERE nome = '$username'";
        $result = mysqli_query($conn, $getUserID);
        $row = mysqli_fetch_assoc($result);
        $userID = $row['IDUsuario'];
        
        $datetime = date('Y-m-d H:i:s');

        // Recebendo a imagem
        // $imagem = $_FILES['imagem']['tmp_name'];
        // $imagem_nome = $_FILES['imagem']['name'];
        // $imagem_tipo = $_FILES['imagem']['type'];

        // Convertendo a imagem para dados binários (blob)
        $imagem_blob = addslashes(file_get_contents($imagem));

        $sql = "
        SET @UltimoID = (SELECT MAX(IDChamado) FROM tbchamados);
        SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
        INSERT INTO tbchamados (IDChamado, assunto, descricao, dataAbertura, status_chamado, responsavel, autor, equipamento, imagem, categoria)
        VALUES (@UltimoID, '$assunto', '$descricao', '$datetime', 1, null, '$userID', $equipamento, '$imagem_blob', '$categoria')
        ";

        if (mysqli_multi_query($conn, $sql)) {
            if (mysqli_affected_rows($conn) > 0) {
                echo "Novo chamado adicionado com sucesso!";
            } else {
                echo "caiu no segundo else-> " .  mysqli_info($conn);
            }
        }

        $conn->close();
    }
}

?>
