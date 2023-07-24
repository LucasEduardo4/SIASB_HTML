

<?php
// excluir_anotacao.php

// Verifica se foi passado um ID válido na URL para excluir a anotação específica
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idAnotacao = $_GET['id'];

    // Realize a conexão com o banco de dados (substitua os valores pelos apropriados para o seu ambiente)
    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $bancoDeDados = "siasb";

    $conexao = mysqli_connect($host, $usuario, $senha, $bancoDeDados);

    // Verifica se a conexão foi bem-sucedida
    if (!$conexao) {
        die("Erro na conexão: " . mysqli_connect_error());
    }

    // Construa e execute a consulta SQL para excluir a anotação do banco de dados
    $consultaExcluir = "DELETE FROM tbagenda WHERE IDAgenda = $idAnotacao";

    if (mysqli_query($conexao, $consultaExcluir)) {
        // A anotação foi excluída com sucesso
        echo "Anotação excluída com sucesso!";
    } else {
        // Ocorreu um erro ao excluir a anotação
        echo "Erro ao excluir a anotação: " . mysqli_error($conexao);
    }

    // Feche a conexão com o banco de dados
    mysqli_close($conexao);
} else {
    // Se não foi fornecido um ID válido na URL, redirecione para a página principal ou exiba uma mensagem de erro
    echo "ID de anotação inválido.";
}
?>
