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
        $existeEquipamento = $_POST['existeEquipamento'];
        // $imagem = $_POST['imagem'];
        // $imagem = '';


        //verifica se o equipamento existe
        //adicionar o equipamento no banco de dados
        if($existeEquipamento == true){
            echo "Entrou aqui \n";
            $sql1 = "
            SET @UltimoID = (SELECT MAX(sti_ID) FROM tbequipamentos);
            SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
            INSERT INTO TBequipamentos(sti_id, descricao,tipo, usuario) values(@UltimoID, $equipamento, 1, '$username')
            ";
            if (mysqli_multi_query($conn, $sql1)) {
                if (mysqli_affected_rows($conn) > 0) {
                    echo "Novo equipamento adicionado com sucesso!";
                } else {
                    echo "caiu no segundo else-> " . mysqli_info($conn); //ver pq caiu no else
                    echo $sql1;
                }
            }else
                echo "Erro ao adicionar equipamento";
        } 



        // SET @UltimoID = (SELECT MAX(sti_ID) FROM tbequipamentos);
        // INSERT INTO TBequipamentos(sti_id, descricao,tipo, usuario) values(@UltimoID, 106, 1, root)
  

        $getUserID = "SELECT IDUsuario FROM TBusuario WHERE nome = '$username'";
        $result = mysqli_query($conn, $getUserID);
        $row = mysqli_fetch_assoc($result);
        $userID = $row['IDUsuario'];

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
        VALUES (@UltimoID, '$assunto', '$descricao', '$datetime', 1, null, '$userID', $equipamento, '$imagem_blob', '$categoria')
        ";

        if (mysqli_multi_query($conn, $sql)) {
            if (mysqli_affected_rows($conn) > 0) {
                echo "Novo chamado adicionado com sucesso!";
            } else {
                echo "caiu no segundo else-> " . mysqli_info($conn); //ver pq caiu no else
                echo $sql;
            }
        }

        $conn->close();
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['stiID'])){
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $stiID = $_POST['stiID'];
        $descricao = '';

        $sql = "SELECT descricao from tbequipamentos WHERE sti_id = $stiID";
        $result = mysqli_query($conn, $sql);
        if($result){
            $row = mysqli_fetch_assoc($result);
            if($row){
                $descricao = $row['descricao'];
            }else
                $descricao = 'Equipamento nao encontrado';
        }else{
            echo "Digite um STI_ID valido (números inteiros)";
        }
        echo $descricao;
    }
}
?>