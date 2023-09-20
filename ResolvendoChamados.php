<?php 
session_start();
$conn = mysqli_connect("localhost", "root", "", "siasb");
// var_dump($_SESSION);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['selectChamados'])){
        $sql = "SELECT IDChamado, assunto, a.nome as 'autor', dataAbertura, dataFechamento, c.descricao, r.nome as 'responsavel', s.descricao as 'status_chamado', p.descricao as 'prioridade' from tbchamados c
        LEFT JOIN tbusuario a on a.IDUsuario = c.autor
        LEFT JOIN tbusuario r on r.IDUsuario = c.responsavel
        LEFT JOIN tbstatus_chamado s on s.IDStatus = c.status_chamado
        LEFT JOIN tbprioridade p on c.prioridade = p.ID
        WHERE status_chamado != 4";
        $result = mysqli_query($conn, $sql);
        $chamados = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $usuario = $_SESSION['username'];
        $usuario = array("usuario" => $usuario);
        $usuario = array($usuario);
        echo json_encode($usuario + $chamados);
    }else
    if(isset($_POST['receberChamado'])){
        $IDChamado = $_POST['IDChamado'];

        $sql = "UPDATE tbchamados SET responsavel = (SELECT IDUsuario FROM tbusuario WHERE nome = '".$_SESSION['username']."'), status_chamado = 2 WHERE IDChamado = $IDChamado";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo json_encode(array("success" => true));
        }else{
            echo json_encode(array("success" => false));
        }
    }
}
?>