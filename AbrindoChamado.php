<?php
//aparentemente está tudo certo com a inserção da imagem. amanha tentar exibir as imagens na DetalhandoChamado
session_start();
if (isset($_POST['teste'])) {
    $_SESSION['username'] = 'gabriel_fernandes';
}
require_once("model/conexao.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verifyEquip']) && !isset($_POST['insertNew'])) {

        $sti_ID = $_POST['sti_ID'];
        $descricao = '';

        $sql = "SELECT descricao from tbequipamentos WHERE sti_id = $sti_ID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $descricao = $row['descricao'];
            } else
                $descricao = 'nao encontrado';
        } else {
            echo "invalido";
        }
        echo $descricao;
    } else
        if (isset($_POST['insertNew']) && isset($_POST['assunto']) && isset($_POST['descricao']) && isset($_POST['sti_ID']) && !isset($_POST['verifyEquip'])) {
            var_dump($_POST);
            $assunto = $_POST['assunto'];
            $descricao = $_POST['descricao'];
            $username = $_SESSION['username'];
            $sti_ID = $_POST['sti_ID'];

            if (isset($_POST['motivo']))
                $motivo = $_POST['motivo'];
            else
                $motivo = null;

            if ($_POST['terceiros'] != "") {
                $terceiros = $_POST['terceiros'];

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
                } else {
                    $terceiros = null;
                    $ID_Terceiro = null;
                }
            } else {
                $terceiros = 'null';
                $ID_Terceiro = 'null';
            }


            $getUserID = "SELECT IDUsuario FROM TBusuario WHERE nome = '$username'";
            $result = mysqli_query($conn, $getUserID);
            $row = mysqli_fetch_assoc($result);
            $userID = $row['IDUsuario'];
            $datetime = date('Y-m-d H:i:s');
            if (isset($_FILES['imagem'])) {
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
            } else
                $imagens = null;


            $sql = "";

            // Itere pelo array de imagens e construa as declarações SQL

            // Inicie a transação
            mysqli_begin_transaction($conn);
            $sqlLastInsert = "SELECT MAX(IDChamado) FROM tbchamados";
            $result = mysqli_query($conn, $sqlLastInsert);
            $row = mysqli_fetch_assoc($result);
            $lastID = $row['MAX(IDChamado)'];
            $lastID = $lastID + 1;
            if ($lastID == null)
                $lastID = 1;

            // Adicione a declaração SQL para inserir o chamado ao final
            $sql = "INSERT INTO tbchamados (IDChamado, assunto, descricao, dataAbertura, status_chamado, responsavel, autor, equipamento, terceiros, motivo) 
        VALUES ($lastID, '$assunto', '$descricao', '$datetime', 1, null, '$userID', '$sti_ID', $ID_Terceiro, '$motivo');";

            echo "\n";
            echo $sql;
            echo "\n";
            // Execute a declaração SQL
            if ($result = mysqli_multi_query($conn, $sql)) {
                // Se a inserção foi bem-sucedida, adicione as imagens
                if ($imagens != null) {
                    foreach ($imagens as $imagem) {
                        $sql_imagens = "INSERT INTO tbimagens (imagem, referencia) VALUES ('{$imagem['blob']}', $lastID);";

                        echo "\n";
                        echo $sql_imagens;
                        echo "\n";
                        if (!mysqli_query($conn, $sql_imagens)) {
                            // Se uma inserção de imagem falhar, reverta a transação
                            mysqli_rollback($conn);
                            echo "Erro ao inserir imagens. Transação revertida.";
                            exit;
                        }
                    }
                }

                // Confirme a transação
                mysqli_commit($conn);
                echo "Transação concluída com sucesso.";
            } else {
                // Se a inserção de chamado falhar, reverta a transação
                mysqli_rollback($conn);
                echo "Erro ao inserir chamado. Transação revertida.";
            }

            // Feche a conexão
            mysqli_close($conn);


            // Avançar para o próximo resultado para evitar erros
            while (mysqli_next_result($conn)) {
                // Certifique-se de lidar com todos os resultados intermediários (pode não haver nenhum)
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            }
            if ($result == false) {
                echo "Erro: " . mysqli_error($conn);
            } else {
                echo "Consultas executadas com sucesso!";
            }

        } else
            if (isset($_POST['verify'])) {
                // var_dump($_SESSION);
                if (!isset($_SESSION['username'])) {
                    echo "false";
                } else {
                    echo 'true';
                }
            }
}
?>