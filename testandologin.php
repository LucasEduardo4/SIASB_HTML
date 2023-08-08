<div class="container">
        <div class="div1">
            <!-- Conteúdo da primeira div aqui -->
            <h1 style="font-size: 15px;">Adicione uma foto de perfil</h1>
    <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="imagem" id="imagemInput">
        <button type="submit">Alterar Foto</button>
    </form>



    <div id="mensagemSucesso" style="display: none; color: green;">
        <p style="padding-top: 5px;"> Foto De Perfil Atualizada com Sucesso !! </p>
    </div>
<!DOCTYPE html>
<html>
<head>
    <title>Display Image from Database</title>
</head>
<body>
    <h1>User Profile Image</h1>
    
    <?php
    // Conexão com o banco de dados (substitua pelas suas informações)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "siasb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão com o banco de dados falhou: " . $conn->connect_error);
    }

    $userID = 1; // Replace with the actual user ID

    $sqlFetchImage = "SELECT icone FROM tbusuario WHERE IDUsuario = ?";
    $stmtFetchImage = $conn->prepare($sqlFetchImage);
    $stmtFetchImage->bind_param("i", $userID);
    $stmtFetchImage->execute();
    $stmtFetchImage->bind_result($imageData);
    $stmtFetchImage->fetch();
    $stmtFetchImage->close();

    $conn->close();

    if ($imageData) {
        // Display the image using base64 encoding
        $base64Image = base64_encode($imageData);
        echo "<img src='data:image/jpeg;base64,$base64Image' alt='User Profile Image'>";
    } else {
        echo "<img src='..\SIASB_HTML\icons\usuario.png' alt='Default Profile Image'>";
    }
    ?>
</body>
</html>
