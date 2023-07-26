<?php
require('tcpdf/tcpdf.php');

// Exemplo de dados
$dados = array(
    // array('IDChamado' => '1', 'descricao' => 'Descrição do chamado 1', 'data_abertura' => '2023-07-26', 'data_fechamento' => '2023-07-28', 'responsavel' => 'Fulano', 'autor' => 'Ciclano'),
    // array('IDChamado' => '2', 'descricao' => 'Descrição do chamado 2', 'data_abertura' => '2023-07-27', 'data_fechamento' => '', 'responsavel' => 'Beltrano', 'autor' => 'Ciclano'),
    // Adicione mais dados aqui, se necessário
);
// ----------------------------------------------------------------------- \\
//                             DOCUMENTATION:                              \\
// ----------------------------------------------------------------------- \\
//  Cell(largura,altura,texto,borda,quebra de linha,alinhamento,fill,link) \\
//


$font = 'times'; //times, courier ou helvetica
$border = 1; // 0 ou 1

// Criação do PDF
$pdf = new TCPDF();
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();
$pdf->SetFont($font, 'B', 16);
$pdf->Cell(0, 10, 'Relatório Exemplo!', 0, 1, 'C');
$pdf->SetFont($font, '', 12);
$pdf->Ln();

$pdf->Cell(0, 10, 'Filtros selecionados:', 0, 1, 'L');
$pdf->Cell(0, 10, 'Data de Abertura: X até Y', 0, 0, 'L');
$pdf->Cell(0, 10, 'Status: Aberto', 0, 0, 'L');




$pdf->Ln();
$larguraString = $pdf->GetStringWidth('IDChamado'); //forma para calcuar o tamanho da celula "automaticamente"
// Cabeçalho das colunas
$pdf->Cell($larguraString + 5, 10, 'IDChamado', $border);
$pdf->Cell($pdf->GetStringWidth('Data Abertura') + 9, 10, 'Descrição', $border);
$pdf->Cell($pdf->GetStringWidth('Data Abertura') + 2, 10, 'Data Abertura', $border);
$pdf->Cell($pdf->GetStringWidth('Data Fechamento') + 2, 10, 'Data Fechamento', $border);
$pdf->Cell($pdf->GetStringWidth('Responsável') + 2, 10, 'Responsável', $border);
$pdf->Cell(20, 10, 'Autor', $border);
$pdf->Cell(20, 10, 'status', $border);
$pdf->Ln();


// Dados
foreach ($dados as $dado) {
    $pdf->Cell(20, 10, $dado['IDChamado'], 1);
    $pdf->Cell(50, 10, $dado['descricao'], 1);
    $pdf->Cell(40, 10, $dado['data_abertura'], 1);
    $pdf->Cell(40, 10, $dado['data_fechamento'], 1);
    $pdf->Cell(30, 10, $dado['responsavel'], 1);
    $pdf->Cell(30, 10, $dado['autor'], 1);
    $pdf->Ln();
}

$pdf->Output('relatorio.pdf', 'I');
?>
