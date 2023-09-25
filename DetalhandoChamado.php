<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");

    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ChamadoID'])) {
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $IDChamado = $_POST['ChamadoID'];

        $sql = "SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, sc.descricao as 'status_chamado', a.nome as 'responsavel', u.nome as 'autor', e.descricao as 'equipamento', p.icone
                FROM TBChamados c
                LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus
                LEFT JOIN TBUsuario a on c.responsavel = a.IDUsuario
                LEFT JOIN TBUsuario u on c.autor = u.IDUsuario    
                LEFT JOIN TBEquipamentos e on c.equipamento = e.sti_ID
                LEFT JOIN TBPessoa p on c.autor = p.IDPessoa
                WHERE IDChamado = $IDChamado";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $resultArray = array();
                    // $resultArray['index'] = $variavel;
                    $IDChamado = $row["IDChamado"];
                    $assunto = $row["assunto"];
                    $descricao = $row["descricao"];
                    $dataAbertura = $row["dataAbertura"];
                    $status_chamado = $row["status_chamado"];
                    $status_chamado = "<p class='statusColors $status_chamado'>$status_chamado</p>";
                    $responsavel = $row["responsavel"];
                    $autor = $row["autor"];
                    $equipamento = $row["equipamento"];
                    // $imagem = $row["imagem"];
                    $icone = $row["icone"];

                    $resultArray['IDChamado'] = $IDChamado;
                    $resultArray['assunto'] = $assunto;
                    $resultArray['descricao'] = $descricao;
                    $resultArray['dataAbertura'] = $dataAbertura;
                    $resultArray['status_chamado'] = $status_chamado;
                    $resultArray['responsavel'] = $responsavel;
                    $resultArray['autor'] = $autor;
                    $resultArray['equipamento'] = $equipamento;
                    // $resultArray['imagem'] = base64_encode($imagem);
                    $resultArray['icone'] = base64_encode($icone);

                    $sql2 = "SELECT imagem from TBimagens WHERE referencia = $IDChamado AND LOG IS NULL";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();

                    $resultImagens = array();
                    if ($result2->num_rows > 0) {
                        while ($row2 = $result2->fetch_assoc()) {
                            $imagem = $row2["imagem"];
                            $resultImagens[] = base64_encode($imagem);
                        }
                    }
                    $resultArray['imagensChamado'] = $resultImagens;
                }
            }
        }

        class Imagem
        {
            public $IDImagem;
            public $dados; // O dado da imagem
            public $referencia;

            public function __construct($IDImagem, $dados, $referencia)
            {
                $this->IDImagem = $IDImagem;
                $this->dados = $dados;
                $this->referencia = $referencia;
            }
        }
        class LogChamado
        {
            public $IDLog;
            public $mensagem;
            public $dataAlteracao;
            public $responsavel;
            public $status;
            public $imagensLog;

            public function __construct($IDLog, $mensagem, $dataAlteracao, $responsavel, $status, $imagens){
                $this->IDLog = $IDLog;
                $this->mensagem = $mensagem;
                $this->dataAlteracao = $dataAlteracao;
                $this->responsavel = $responsavel;
                $this->status = $status;
                $this->imagensLog = $imagens;
            }
        }

        $sql2 = "SELECT IDLog, mensagem, dataAlteracao, u.nome as 'responsavel', sc.descricao as 'status', referencia FROM TBLog_chamado
                left join tbusuario u on responsavel = u.IDUsuario
                left join tbstatus_chamado sc on status = sc.IDStatus WHERE referencia = $IDChamado";
                // echo $sql2;

        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $resultLogs = array();
        if ($result2->num_rows > 0) {

            while ($row2 = $result2->fetch_assoc()) {
                $logChamado = new LogChamado(
                    $row2["IDLog"],
                    $row2["mensagem"],
                    $row2["dataAlteracao"],
                    $row2["responsavel"],
                    $row2["status"],
                    $imagens = array()
                );

                $sqlImagens = "SELECT * FROM tbimagens WHERE LOG IS NOT NULL AND referencia = " . $row2["IDLog"];
                $stmtImagens = $conn->prepare($sqlImagens);
                $stmtImagens->execute();
                $resultImagens = $stmtImagens->get_result();

                if ($resultImagens->num_rows > 0) {
                    while ($rowImagem = $resultImagens->fetch_assoc()) {
                        $imagemencoded = base64_encode($rowImagem["imagem"]);
                        $imagem = new Imagem(
                            $rowImagem["IDImagem"],
                            $imagemencoded,
                            $rowImagem["referencia"]
                        );
                        $logChamado->imagensLog[] = $imagem;

                    }
                }

                $resultLogs[] = $logChamado;
                // echo json_encode($resultLogs);
            }
        }
        $resultArray['logs'] = $resultLogs;

        echo json_encode($resultArray);
        // var_dump($resultArray);

    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['gerarLog'])) {
        // var_dump($_FILES);
        $conn = mysqli_connect("localhost", "root", "", "siasb");
        $status = $_POST['status'];
        $mensagem = $_POST['mensagem'];
        $referencia = $_POST['referencia'];
        $responsavel = $_SESSION['username'];
        // if (isset($_FILES['imagem'])) {
        //     // Recebendo a imagem
        //     $imagem = $_FILES['imagem']['tmp_name'];
        //     $imagem_nome = $_FILES['imagem']['name'];
        //     $imagem_tipo = $_FILES['imagem']['type'];

        //     // Convertendo a imagem para dados binários (blob)
        //     $imagem_blob = addslashes(file_get_contents($imagem));
        // } else {
        //     $imagem_blob = null;
        // }
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

        $sql = "SET @UltimoID = (SELECT MAX(IDLog) FROM tblog_chamado);
        SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
        SET @responsavel = (SELECT IDUsuario FROM tbusuario WHERE nome = '$responsavel');
        INSERT INTO tblog_chamado(IDLog, mensagem, dataAlteracao, responsavel, status, referencia)
        VALUES (NULL, '$mensagem', NOW(), @responsavel, $status, $referencia);";
        // echo $sql;
        foreach ($imagens as $imagem) {
            $sql .= "INSERT INTO tbimagens (imagem, referencia, log) VALUES ('{$imagem['blob']}', @UltimoID, '1');";
            // 
        }
        // echo $sql;
        if (mysqli_multi_query($conn, $sql)) {
            echo "Novo status do chamado adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar novo status de chamado: " . mysqli_error($conn);
        }
        // echo $sql;
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verificarSetor'])) {
        $conn = mysqli_connect("localhost", "root", "", "siasb");
        $IDUsuario = $_SESSION['username'];
        $sql = "SELECT p.setor_secao FROM TBUsuario u
                join tbpessoa p on p.IDPessoa = u.IDUsuario
                where u.nome = '$IDUsuario'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $setor = $row["setor_secao"];
                    if ($setor == '1') {
                        echo "1"; //tecnologia
                    } else
                        echo "0"; //qqr outro
                }
            }
        }
    }
}
?>