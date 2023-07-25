<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['ChamadoID'])){
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $IDChamado = $_POST['ChamadoID'];

        // $sql = "SELECT * FROM tbchamados WHERE IDChamado = $IDChamado"; // fazer os joins, pra retornar os nomes ao inves dos ID's.
        $sql = "SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, sc.descricao as 'status_chamado', a.nome as 'responsavel', u.nome as 'autor', e.descricao as 'equipamento', c.imagem, c.categoria
                FROM TBChamados c
                LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus
                LEFT JOIN TBUsuario a on c.responsavel = a.IDUsuario
                LEFT JOIN TBUsuario u on c.autor = u.IDUsuario    
                LEFT JOIN TBEquipamentos e on c.equipamento = e.sti_ID
                WHERE IDChamado = $IDChamado";
                // LEFT JOIN tbligacaochamados_log lcl on c.IDChamado = lcl.IDChamado
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            if($result->num_rows > 0){
                while ($row = $result->fetch_assoc()) {
                    $resultArray = array();
                    // $resultArray['index'] = $variavel;
                    $IDChamado = $row["IDChamado"];
                    $assunto = $row["assunto"];
                    $descricao = $row["descricao"];
                    $dataAbertura = $row["dataAbertura"];
                    $status_chamado = $row["status_chamado"];
                    $responsavel = $row["responsavel"];
                    $autor = $row["autor"];
                    $equipamento = $row["equipamento"];
                    $imagem = $row["imagem"];
                    $categoria = $row["categoria"];
                    // $ligacaoChamadoID = $row["ligacaoChamadoID"];
                    
                    $resultArray['IDChamado'] = $IDChamado;
                    $resultArray['assunto'] = $assunto;
                    $resultArray['descricao'] = $descricao;
                    $resultArray['dataAbertura'] = $dataAbertura;
                    $resultArray['status_chamado'] = $status_chamado;
                    $resultArray['responsavel'] = $responsavel;
                    $resultArray['autor'] = $autor;
                    $resultArray['equipamento'] = $equipamento;
                    $resultArray['imagem'] = $imagem;
                    $resultArray['categoria'] = $categoria;
                    // $resultArray['ligacaoChamadoID'] = $ligacaoChamadoID;
                }
            }
        }

        class LogChamado {
            public $IDLog;
            public $mensagem;
            public $dataAlteracao;
            public $responsavel;
            public $status;
        
            public function __construct($IDLog, $mensagem, $dataAlteracao, $responsavel, $status) {
                $this->IDLog = $IDLog;
                $this->mensagem = $mensagem;
                $this->dataAlteracao = $dataAlteracao;
                $this->responsavel = $responsavel;
                $this->status = $status;
            }
        }
        
        $sql2 = "SELECT IDLog, mensagem, dataAlteracao, u.nome as 'responsavel', sc.descricao as 'status', referencia FROM TBLog_chamado
                left join tbusuario u on responsavel = u.IDUsuario
                left join tbstatus_chamado sc on status = sc.IDStatus WHERE referencia = $IDChamado";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        
        $resultLogs = array();
        if ($result->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $logChamado = new LogChamado(
                    $row2["IDLog"],
                    $row2["mensagem"],
                    $row2["dataAlteracao"],
                    $row2["responsavel"],
                    $row2["status"]
                );
                $resultLogs[] = $logChamado;
            }
        }
        
        $resultArray['logs'] = $resultLogs;
            
            echo json_encode($resultArray);
            
    
        
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['gerarLog'])){
        $conn = mysqli_connect("localhost", "root", "", "siasb");
    
        $status = $_POST['status'];
        $mensagem = $_POST['mensagem'];
        $referencia = $_POST['referencia'];
        $responsavel = $_SESSION['username'];

        $sql="SET @responsavel = (SELECT IDUsuario FROM tbusuario WHERE nome = '$responsavel');
        INSERT INTO tblog_chamado(IDLog, mensagem, dataAlteracao, responsavel, status, referencia)
        VALUES (NULL, '$mensagem', NOW(), @responsavel, $status, $referencia)";
        echo $sql;
        if (mysqli_multi_query($conn, $sql)) {
            echo "Novo status do chamado adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar novo status de chamado: " . mysqli_error($conn);
        }
            // echo $sql;
        $conn->close();
    }
}

if($_SERVER['REQUEST_METHOD']=== 'POST'){
    if(isset($_POST['verificarSetor'])){
        $conn = mysqli_connect("localhost", "root", "", "siasb");
        $IDUsuario = $_SESSION['username'];
        $sql = "SELECT p.setor FROM TBUsuario u
                join tbpessoa p on p.IDPessoa = u.IDUsuario
                where u.nome = '$IDUsuario'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $setor = $row["setor"];
                    if($setor == '1'){
                        echo "1"; //tecnologia
                    }else
                        echo "0"; //qqr outro
                }
            }
        }
    }
}

?>