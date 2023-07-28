<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview</title>
    <style>
    </style>
</head>
<body style="margin: 30px;">
    
</body>
</html>

<?php
// Verifica se a requisição é do tipo POST
echo "preciso mandar uma requisição, pelo metodo post, aqui pra essa parte.";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $filtroDataAbertura = $_POST['filtroDataAbertura'] ?? null;
    $filtroDataFechamento = $_POST['filtroDataFechamento'] ?? null;
    $filtroResponsavel = $_POST['filtroResponsavel'] ?? null;
    $filtroAutor = $_POST['filtroAutor'] ?? null;
    $filtroTipo = $_POST['filtroTipo'] ?? null;
    $filtroEquipamento = $_POST['filtroEquipamento'] ?? null;
    $filtroSetor = $_POST['filtroSetor'] ?? null;
    $ordenarPor = $_POST['ordenar'] ?? null;
    $ordem = $_POST['ordem'] ?? null;
    $agruparPor = $_POST['agruparPor'] ?? null;

    // Agora você pode usar esses dados para construir sua consulta SQL
    // Por exemplo:
    $sql = "SELECT * FROM tbchamados WHERE 1=1";

    if (!empty($filtroDataAbertura)) {
        $sql .= " AND dataAbertura >= '$filtroDataAbertura'";
    }

    if (!empty($filtroDataFechamento)) {
        $sql .= " AND dataFechamento <= '$filtroDataFechamento'";
    }

    if (!empty($filtroResponsavel)) {
        $sql .= " AND responsavel = '$filtroResponsavel'";
    }

    if (!empty($filtroAutor)) {
        $sql .= " AND autor = '$filtroAutor'";
    }

    if (!empty($filtroTipo)) {
        $sql .= " AND status_chamado = '$filtroTipo'";
    }

    if (!empty($filtroEquipamento)) {
        $sql .= " AND equipamento = '$filtroEquipamento'";
    }

    if (!empty($filtroSetor)) {
        $sql .= " AND setor = '$filtroSetor'";
    }

    // Ordenação
    if (!empty($ordenarPor)) {
        $sql .= " ORDER BY $ordenarPor";
        if ($ordem === 'decrescente') {
            $sql .= " DESC";
        }
    }
    
    $resultado = $conexao->query($sql);
    var_dump($resultado);
    echo 'teste';
    // Executar a consulta no banco de dados e gerar o relatório em PDF ou exibir na página,
    // dependendo da lógica do seu sistema.

    // Exemplo de como gerar o relatório em PDF usando TCPDF (assumindo que você já configurou a conexão com o banco de dados):
    require('tcpdf/tcpdf.php');

    // Crie um objeto TCPDF e configure o cabeçalho e rodapé, se necessário.
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Execute a consulta e itere sobre os resultados para construir o relatório.
    // Por exemplo:
    while ($row = $resultado->fetch_assoc()) {
        // Construa o conteúdo do relatório usando $row['nome_do_campo'] para acessar os dados do banco de dados.
        // Adicione as células do TCPDF conforme necessário.
        // $pdf->Cell(...);
    }

    // Saída do relatório em PDF
    // $pdf->Output('relatorio.pdf', 'I');
}

?>