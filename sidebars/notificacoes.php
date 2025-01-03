<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'siasb');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verifyUser'])) {


        // echo $_SESSION['username'];
        $username = $_SESSION['username'];
        $sql = "SELECT IDUsuario from tbusuario where nome = '$username'";
        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row["IDUsuario"];
                }
            }
        } else {
            echo "Erro na consulta SQL: " . $conn->error;
        }

        class Notificacoes
        {
            public $IDNotificacao;
            public $status;
            public $visualizado;
            public $excluido;
            public $destino;
            public $chamadoReferencia;
            public $data;

            public function __construct($IDNotificacao, $status, $visualizado, $excluido, $destino, $chamadoReferencia, $data)
            {
                $this->IDNotificacao = $IDNotificacao;
                $this->status = $status;
                $this->visualizado = $visualizado;
                $this->excluido = $excluido;
                $this->destino = $destino;
                $this->chamadoReferencia = $chamadoReferencia;
                $this->data = $data;
            }
        }
        $resultNotificacoes = array();
        $sql = "SELECT * from tbnotificacoes where destino = '$id' ORDER BY data DESC";
        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $IDNotificacao = $row["IDNotificacao"];
                    $status = $row["status"];
                    $visualizado = $row["visualizado"];
                    $excluido = $row["excluido"];
                    $destino = $row["destino"];
                    $chamadoReferencia = $row["chamadoReferencia"];
                    $data = $row["data"];

                    $notificacao = new Notificacoes(
                        $IDNotificacao,
                        $status,
                        $visualizado,
                        $excluido,
                        $destino,
                        $chamadoReferencia,
                        $data
                    );
                    $resultNotificacoes[] = $notificacao;

                    // }
                }
            }
        } else {
            echo "Erro na consulta SQL: " . $conn->error;
        }
        echo json_encode($resultNotificacoes);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clicouNotificacao'])) {
        $idNotificacao = $_POST['clicouNotificacao'];
        $sql = "UPDATE tbnotificacoes SET visualizado = 1 WHERE IDNotificacao = '$idNotificacao'";
        $result = $conn->query($sql);
        // if($result){
        //     echo "Notificação visualizada";
        // }else{
        //     echo "Erro na consulta SQL: " . $conn->error;
        // }
    }
    ;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['excluiNotificacao'])) {
        $idNotificacao = $_POST['excluiNotificacao'];
        $sql = "UPDATE tbnotificacoes SET excluido = 1 WHERE IDNotificacao = '$idNotificacao'";
        $result = $conn->query($sql);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['limpaNotificacoes'])) {
        $idUsuario = $_SESSION['username'];

        $sql = "SELECT IDNotificacao FROM TBNotificacoes
                LEFT JOIN TBChamados c on chamadoReferencia = c.IDChamado
                LEFT JOIN TBUsuario u on c.autor = u.IDUsuario
                where u.nome = '$idUsuario' and visualizado = 1 and excluido <> 1;";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultSelect = $stmt->get_result(); // Usar uma variável diferente

        if ($resultSelect && $resultSelect->num_rows > 0) {
            while ($row = $resultSelect->fetch_assoc()) {
                $IDNotificacao = $row["IDNotificacao"];
                $sql1 = "UPDATE tbnotificacoes SET excluido = 1 WHERE IDNotificacao = '$IDNotificacao'";
                $resultUpdate = $conn->query($sql1); // Usar uma variável diferente
            }
        }
    }
}



?>