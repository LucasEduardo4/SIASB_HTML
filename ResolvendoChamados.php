<?php 
session_start();
$conn = mysqli_connect("localhost", "root", "", "siasb");
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['selectChamados'])){
        $sql = "SELECT * from tbchamados";
        $result = mysqli_query($conn, $sql);
        $chamados = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $usuario = $_SESSION['username'];
        $usuario = array("usuario" => $usuario);
        $usuario = array($usuario);
        echo json_encode($usuario + $chamados);

    }
}
?>