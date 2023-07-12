<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $conn = mysqli_connect("localhost", "root", "", "siasb");

    if (isset($_POST['assunto']) && isset($_POST['descricao']) && isset($_POST['equipamento']) && isset($_POST['categoria'])) {
        $assunto = $_POST['assunto'];
        $descricao = $_POST['descricao'];
        $equipamento = $_POST['equipamento'];
        $categoria = $_POST['categoria'];
        $username = $_SESSION['username'];

        $getUserID = "SELECT IDUsuario FROM TBusuario WHERE nome = '$username'";
        $result = mysqli_query($conn, $getUserID);
        $row = mysqli_fetch_assoc($result);
        $userID = $row['IDUsuario'];
        

        $datetime = date('Y-m-d H:i:s');
        echo $assunto; 
        echo " <- assunto | ";
        echo $descricao;
        echo " <- descricao | ";
        echo $equipamento;
        echo " <- equipamento | ";
        echo $categoria;
        echo " <- categoria | ";
        echo $userID;
        echo " <- userID | ";
        echo $datetime;
        echo " <- datetime | ";
        
        $sql = "
        SET @UltimoID = (SELECT MAX(IDChamado) FROM tbchamados);
        SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
        INSERT INTO tbchamados (IDChamado, assunto, descricao, dataAbertura, status_chamado, responsavel, autor, equipamento, imagem ,categoria)
        VALUES (@UltimoID, '$assunto', '$descricao', '$datetime', 1, null, '$userID', $equipamento, '', '$categoria')
        ";

        if (mysqli_multi_query($conn, $sql)) {
            if (mysqli_affected_rows($conn) > 0) {
                    echo "Novo chamado adicionado com sucesso!";
            } else {
                echo "caiu no segundo else-> " .  mysqli_info($conn);
            }
        

        $conn->close();
    }
}
}
?>
