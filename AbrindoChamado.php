<?php
//aparentemente está tudo certo com a inserção da imagem. amanha tentar exibir as imagens na DetalhandoChamado
session_start();
$conn = mysqli_connect("localhost", "root", "", "siasb");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sti_ID'])) {

        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $sti_ID = $_POST['sti_ID'];
        $descricao = '';

        $sql = "SELECT descricao from tbequipamentos WHERE sti_id = $sti_ID";
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
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    if (isset($_POST['assunto']) && isset($_POST['descricao']) && isset($_POST['sti_ID']) && isset($_POST['terceiros']) && isset($_POST['motivo']) ) {
        $assunto = $_POST['assunto'];
        $descricao = $_POST['descricao'];
        $username = $_SESSION['username'];
        $sti_ID = $_POST['sti_ID'];
        $terceiros = $_POST['terceiros'];
        $motivo = $_POST['motivo'];

        // REALIZAR AQUI A VERIFICAÇÃO PARA VER SE O NOME BATE COM A PESSOA E ATUALIZAR A VARIAVEL PARA O ID
        $sql = "SELECT * FROM tbpessoa WHERE nomeCompleto = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $terceiros);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
        // Encontrou resultados
        $row = $result->fetch_assoc();
        $ID_Terceiro = $row["IDPessoa"];
        }
        // MELHOR MÉTODO PARA PUCHAR DADOS DE COM VARIAVEL CORRESPONDENTE
        
        $getUserID = "SELECT IDUsuario FROM TBusuario WHERE nome = '$username'";
        $result = mysqli_query($conn, $getUserID);
        $row = mysqli_fetch_assoc($result);
        $userID = $row['IDUsuario'];
        $datetime = date('Y-m-d H:i:s');
        $imagens = array();

        foreach ($_FILES['imagem']['tmp_name'] as $key => $tmp_name) {
            // Receba a imagem
            $imagem_temp = $_FILES['imagem']['tmp_name'][$key];
            $imagem_nome = $_FILES['imagem']['name'][$key];
            $imagem_tipo = $_FILES['imagem']['type'][$key];

            // Verifique se o arquivo é uma imagem (você pode adicionar validações adicionais)
            if (strpos($imagem_tipo, 'image') !== false) {
                // Converta a imagem para dados binários (blob)
                $imagem_blob = addslashes(file_get_contents($imagem_temp));

                // Armazene os dados da imagem em um array
                $imagens[] = array(
                    'nome' => $imagem_nome,
                    'tipo' => $imagem_tipo,
                    'blob' => $imagem_blob
                );
            }
        }
        
        $sql = "";

        // Itere pelo array de imagens e construa as declarações SQL
        
        // Adicione a declaração SQL para inserir o chamado ao final
        $sql .= "SET @UltimoID = (SELECT MAX(IDChamado) FROM tbchamados);
        SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
        INSERT INTO tbchamados (IDChamado, assunto, descricao, dataAbertura, status_chamado, responsavel, autor, equipamento,terceiros,motivo) 
        VALUES (@UltimoID, '$assunto', '$descricao', '$datetime', 1, null, '$userID', '$sti_ID', '$ID_Terceiro','$motivo');";

        foreach ($imagens as $imagem) {
            $sql .= "INSERT INTO tbimagens (imagem, referencia) VALUES ('{$imagem['blob']}', @UltimoID);";
        }

        // Execute as declarações SQL em uma única chamada mysqli_multi_query
        $result = mysqli_multi_query($conn, $sql);

        if ($result) {
            echo "Novo chamado adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar chamado: " . mysqli_error($conn);
            echo "\n";
            echo $sql;
        }

    }

}

?>