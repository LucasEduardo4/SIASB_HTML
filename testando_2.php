<form method="post" enctype="multipart/form-data">
  <input type="file" name="images[]" multiple accept="image/*">
  <input type="submit" value="Enviar Imagens">
</form>


<?php
// Conexão com o banco de dados (substitua pelas suas credenciais)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siasb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["images"])) {
    $idchamado = 22; // ID do chamado desejado

    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
        $image_name = $_FILES["images"]["name"][$key];
        $image_tmp = $_FILES["images"]["tmp_name"][$key];

        // Verifique se o arquivo é uma imagem
        $image_info = getimagesize($image_tmp);
        if ($image_info === false) {
            echo "O arquivo '$image_name' não é uma imagem válida. Ignorando...";
            continue;
        }

        // Leitura dos dados binários da imagem
        $image_data = file_get_contents($image_tmp);

        // Preparar a consulta SQL para inserir os dados da imagem
        $stmt = $conn->prepare("INSERT INTO tbchamados (IDChamado, imagem) VALUES (?, ?)");
        $stmt->bind_param("ib", $idchamado, $image_data);
        if ($stmt->execute()) {
            echo "Imagem '$image_name' enviada com sucesso!";
        } else {
            echo "Erro ao enviar a imagem '$image_name': " . $stmt->error;
        }
        $stmt->close();
    }

    // Feche a conexão com o banco de dados
    $conn->close();
} else {
    echo "Requisição inválida.";
}
?>


