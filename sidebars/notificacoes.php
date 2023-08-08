<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = mysqli_connect('localhost', 'root', '', 'siasb');
    if (isset($_POST['verifyUser']));
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

    class Notificacoes{
        public $IDNotificacao;
        public $status;
        public $visualizado;
        public $excluido;
        public $destino;
        public $chamadoReferencia;
        public $data;
    
        public function __construct($IDNotificacao, $status, $visualizado, $excluido, $destino, $chamadoReferencia, $data) {
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
    $sql = "SELECT * from tbnotificacoes where destino = '$id'";
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



?>
