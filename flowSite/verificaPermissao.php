<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");

    exit();
}else
if(isset($_POST['verificaPermissao']) || isset($_POST['verificaAtivo'])){
    $conn = mysqli_connect('localhost', 'root', '', 'siasb');
    $username = $_SESSION['username'];

    $sql =  "SELECT * FROM TBUsuario u  
    LEFT JOIN TBPessoa p on p.IDPessoa = u.IDUsuario
    WHERE u.nome = '$username'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $habilitado = $row["habilitado"];
            $setor_secao = $row["setor_secao"];
            $administrador = $row["administrador"];

            if(isset($_POST['verificaPermissao'])){
                if($administrador == 1 && $habilitado == 1){
                    echo 'permitido!';
                }else
                if($habilitado != 1){
                    echo 'desabilitado';
                }else
                if($setor_secao != 1){
                    echo 'sem permissao';
                }else if($habilitado != 1 && $setor_secao != 1){
                    echo 'desabilitado';
                }else{
                    echo 'permitido!';
                }
            }else
            if(isset($_POST['verificaAtivo'])){
                if($habilitado != 1){
                    echo 'desabilitado';
                }else{
                    echo 'permitido!';
                }
            }

        }
    }
}

?>