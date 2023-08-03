<?php
require('tcpdf/tcpdf.php');

$conn = mysqli_connect('localhost', 'root', '', 'siasb');

$sql = "SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, c.dataFechamento, sc.descricao as 'status_chamado', a.nome as 'responsavel', u.nome as 'autor', e.descricao as 'equipamento', c.imagem, c.categoria
FROM TBChamados c
LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus
LEFT JOIN TBUsuario a on c.responsavel = a.IDUsuario
LEFT JOIN TBUsuario u on c.autor = u.IDUsuario    
LEFT JOIN TBEquipamentos e on c.equipamento = e.sti_ID";
// LEFT JOIN tbligacaochamados_log lcl on c.IDChamado = lcl.IDChamado
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$dados = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $IDChamado = $row["IDChamado"];
        $assunto = $row["assunto"];
        $descricao = $row["descricao"];
        $dataAbertura = $row["dataAbertura"];
        $dataFechamento = $row["dataFechamento"];
        $status_chamado = $row["status_chamado"];
        $responsavel = $row["responsavel"];
        $equipamento = $row["equipamento"];
        $autor = $row["autor"];

        // Adicionar o registro atual ao array $dados como um novo elemento
        $dados[] = array(
            'IDChamado' => $IDChamado,
            'assunto' => $assunto,
            'descricao' => $descricao,
            'dataAbertura' => $dataAbertura,
            'dataFechamento' => $dataFechamento,
            'status_chamado' => $status_chamado,
            'responsavel' => $responsavel,
            'equipamento' => $equipamento,
            'autor' => $autor
        );
    }

    function imprimeDados($dados)
    {

        echo "<pre>";
        // print_r($dados);
        foreach ($dados as $registro) {
            foreach ($registro as $campo => $valor) {
                echo "$campo: $valor<br>";
            }
            echo "<br>";
        }

        echo "</pre>";
    }
}
// ----------------------------------------------------------------------- \\
//                             PDF DOCUMENTATION:                          \\
// ----------------------------------------------------------------------- \\
//  Cell(largura,altura,texto,borda,quebra de linha,alinhamento,fill,link) \\

// ------------------ FUNCTIONS --------------------- \\
function convertData($data)
{
    $dataHora = DateTime::createFromFormat('Y-m-d H:i:s', $data);
    if ($dataHora === false) {
        return "--";
    }

    return $dataHora->format('d/m/y H:i');
}
// -------------------- GLOBAL ---------------------- \\
$font = 'times';
$border = 0;

// ---------------------- MAIN ---------------------- \\

$pdf = new TCPDF();
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();
// $pdf->setPageOrientation('L'); //modo paisagem
$pdf->SetFont($font, 'B', 16);
$pdf->Cell(0, 10, 'Relatório de Chamados', 0, 1, 'C');
$pdf->SetFont($font, '', 12);
$pdf->Ln();

// ------------------ CONFIGURAÇÕES ------------------ \\

$LID = $pdf->GetStringWidth('ID') + 5;
$Lassunto = $pdf->GetStringWidth('Assunto') + 10;
// $Ldescricao = $pdf->GetStringWidth('Descrição do chamado') + 9;
$LdataAbertura = $pdf->GetStringWidth('Data Abertura') + 10;
$LdataFechamento = $pdf->GetStringWidth('Data Fechamento') + 2;
$Lresponsavel = $pdf->GetStringWidth('responsavel') + 7;
$Lequipamento = $pdf->GetStringWidth('Equipamento') + 10;
$Lautor = $pdf->GetStringWidth('Autor') + 8;
$Lstatus = $pdf->GetStringWidth('Status') + 8;
$alturaResults = 5;

// --------------------------------------------------- \\


$pdf->Cell(0, 10, 'Filtros selecionados:', 0, 1, 'L');
$pdf->Cell(0, 10, 'Período:', 0, 1, 'L');
$pdf->Cell(0, 10, 'Whatever.. depois eu coloco coisas aqui');
// $pdf->Cell($pdf->GetStringWidth('Data de Abertura: X até Y') + 20, 10, 'Data de Abertura: X até Y', 0, 0, 'C');
// $pdf->Cell(0, 10, 'Status: Aberto', 0, 0, 'C');

$pdf->Ln();
$pdf->Line(10, 60, 200, 60);

$pdf->Cell($LID, 10, 'ID', $border, 0, 'C');
$pdf->Cell($Lassunto, 10, 'Assunto', $border, 0, 'C');
// $pdf->Cell($Ldescricao, 10, 'descricao', $border, 0, 'C');
$pdf->Cell($LdataAbertura, 10, 'Data Abertura', $border, 0, 'C');
$pdf->Cell($LdataFechamento, 10, 'Data Fechamento', $border, 0, 'C');
$pdf->Cell($Lresponsavel, 10, 'Responsável', $border, 0, 'C');
$pdf->Cell($Lequipamento, 10, 'Equipamento', $border, 0, 'C');
$pdf->Cell($Lautor, 10, 'autor', $border, 0, 'C');
$pdf->Cell($Lstatus, 10, 'status', $border, 0, 'C');


$pdf->Ln();
$pdf->SetFont($font, '', 10);

foreach ($dados as $registro) {
    $pdf->Cell($LID, $alturaResults, $registro['IDChamado'], $border, 0, 'C');
    $pdf->Cell($Lassunto, $alturaResults, $registro['assunto'], $border, 0, 'C');
    // $pdf->Cell($Ldescricao, $alturaResults, $registro['descricao'], $border, 0, 'C');
    $pdf->Cell($LdataAbertura, $alturaResults, convertData($registro['dataAbertura']), $border, 0, 'C');
    $pdf->Cell($LdataFechamento, $alturaResults, convertData($registro['dataFechamento']), $border, 0, 'C');
    // Usar MultiCell para permitir texto em várias linhas na coluna "responsavel"
    $pdf->Cell($Lresponsavel, $alturaResults, $registro['responsavel'], $border, 0, 'C');
    $pdf->Cell($Lequipamento, $alturaResults, $registro['equipamento'], $border, 0, 'C');
    $pdf->Cell($Lautor, $alturaResults, $registro['autor'], $border, 0, 'C');
    $pdf->Cell($Lstatus, $alturaResults, $registro['status_chamado'], $border, 0, 'C');
    $pdf->Ln();
}

$pdf->Output('relatorio.pdf', 'I');
// imprimeDados($dados);
?>