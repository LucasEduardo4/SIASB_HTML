<?php
require('tcpdf/tcpdf.php');

// Exemplo de dados
$dados = array(
    array('IDChamado' => '1', 'descricao' => 'Descrição do chamado 1', 'data_abertura' => '2023-07-26', 'data_fechamento' => '2023-07-28', 'responsavel' => 'Fulano', 'autor' => 'Ciclano', 'status' => 'Fechado'),
    array('IDChamado' => '2', 'descricao' => 'Descrição do chamado 2', 'data_abertura' => '2023-07-27', 'data_fechamento' => '', 'responsavel' => 'Beltrano', 'autor' => 'Ciclano', 'status' => 'Fechado'),
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
$pdf->Cell(0, 10, 'Período:', 0, 1, 'L');
$pdf->Cell($pdf->GetStringWidth('Data de Abertura: X até Y') + 20, 10, 'Data de Abertura: X até Y', 0, 0, 'C');
$pdf->Cell(0, 10, 'Status: Aberto', 0, 0, 'C');

$pdf->Ln();
$larguraString = $pdf->GetStringWidth('ID') + 5; //forma para calcuar o tamanho da celula "automaticamente"
$descricaoChamado = $pdf->GetStringWidth('Descrição do chamado') + 9;
$dataAbertura = $pdf->GetStringWidth('Data Abertura') + 2;
$dataFechamento = $pdf->GetStringWidth('Data Fechamento') + 2;
$respopnsavel = $pdf->GetStringWidth('Responsável') + 2;
$autor = $pdf->GetStringWidth('Autor') + 10;
$status = $pdf->GetStringWidth('Status') + 8;

// Cabeçalho das colunas
$pdf->Cell($larguraString, 10, 'ID', $border, 0, 'C');
$pdf->Cell($descricaoChamado, 10, 'Descrição', $border, 0, 'C');
$pdf->Cell($dataAbertura, 10, 'Data Abertura', $border, 0, 'C');
$pdf->Cell($dataFechamento, 10, 'Data Fechamento', $border, 0, 'C');
$pdf->Cell($respopnsavel, 10, 'Responsável', $border, 0, 'C');
$pdf->Cell($autor, 10, 'Autor', $border, 0, 'C');
$pdf->Cell($status, 10, 'Status', $border, 0, 'C');
$pdf->Ln();


// Dados
foreach ($dados as $dado) {
    $pdf->Cell($larguraString, 10, $dado['IDChamado'], $border, 0, 'C');
    $pdf->Cell($descricaoChamado, 10, $dado['descricao'], $border, 0, 'C');
    $pdf->Cell($dataAbertura, 10, $dado['data_abertura'], $border, 0, 'C');
    $pdf->Cell($dataFechamento, 10, $dado['data_fechamento'], $border, 0, 'C');
    $pdf->Cell($respopnsavel, 10, $dado['responsavel'], $border, 0, 'C');
    $pdf->Cell($autor, 10, $dado['autor'], $border, 0, 'C');
    $pdf->Cell($status, 10, $dado['status'], $border, 0, 'C');
    $pdf->Ln();
}

$pdf->Output('relatorio.pdf', 'I');
?>
