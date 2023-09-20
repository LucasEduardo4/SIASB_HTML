<?php
$conn = new mysqli('localhost', 'root', '', 'siasb');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['email'])){
        var_dump($_POST);
        $email = $_POST['email'];
        $sql = "SELECT * FROM tbusuarios WHERE email = '$email'";
        $sql = "SELECT u.IDUsuario FROM tbpessoa p 
                LEFT JOIN tbusuario u on u.idusuario = p.idpessoa
                WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_num_rows($result);
        $token = md5(rand(0,9999).rand(0,9999));
        $link = "http://localhost/Projeto%20PHP%20-%20Login/redefinirSenha.php?token=".$token;
        $mensagem = "Clique no link para redefinir sua senha: ".$link;
        echo $token;
        echo $link;
        echo $mensagem;
        if($row > 0){
            $sql = "INSERT INTO usuarios_token (id_usuario, hash, expirado_em) VALUES ('$id', '$token', NOW() + INTERVAL 1 HOUR)";
            $result = mysqli_query($conn, $sql);
            $assunto = "Redefinição de senha";
            $headers = 'From: ' . $email . "\r\n" .
            'Reply-To: ' . $email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            mail($email, $assunto, $mensagem, $headers);
            echo "Link enviado para o email";
            exit;
        }
    }
}
?>