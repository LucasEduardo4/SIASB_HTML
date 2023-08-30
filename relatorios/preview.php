<?php
function convertData($data)
{
    $dataHora = DateTime::createFromFormat('Y-m-d H:i:s', $data);
    if ($dataHora === false) {
        $dataHora = DateTime::createFromFormat(('Y-m-d'), $data);
        if ($dataHora === false) {
            return "--";
        }
    }

    return $dataHora->format('d/m/y');
}

function convertDataXLSX($data){
    $dataHora = DateTime::createFromFormat('Y-m-d H:i:s', $data);
    if ($dataHora === false) {
        $dataHora = DateTime::createFromFormat(('Y-m-d'), $data);
        if ($dataHora === false) {
            return "--";
        }
    }

    return $dataHora->format('d/m/Y');
}

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['SelectInicial'])) {
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $resultArray = array();

        //responsavel
        $sql1 = "SELECT u.IDUsuario, p.nomeCompleto FROM TBUsuario u
        left join tbpessoa p on u.IDUsuario = p.IDPessoa
        WHERE p.setor = 1;";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        $responsavel = array();
        while ($row1 = $result1->fetch_assoc()) {
            $IDUsuario = $row1["IDUsuario"];
            $nomeCompleto = $row1["nomeCompleto"];
            $responsaveis[$IDUsuario] = $nomeCompleto;
        }
        $resultArray['responsaveis'] = $responsaveis;

        // Consulta 2: SELECT * FROM TBSetor
        $sql2 = "SELECT DISTINCT u.IDUsuario, u.nome FROM tbchamados c
        left join tbusuario u on c.autor = u.IDUsuario";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $usuario = array();
        while ($row2 = $result2->fetch_assoc()) {
            $IDUsuario = $row2["IDUsuario"];
            $nome = $row2["nome"];
            $usuarios[$IDUsuario] = $nome;
        }
        $resultArray['usuarios'] = $usuarios;

        $stmt1->close();
        $stmt2->close();


        $sql3 = "SELECT DISTINCT * FROM tbstatus_chamado";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->execute();
        $result3 = $stmt3->get_result();

        $status = array();

        while ($row3 = $result3->fetch_assoc()) {
            $IDStatus = $row3["IDStatus"];
            $descricao = $row3["descricao"];
            $status[$IDStatus] = $descricao;
        }
        $resultArray['status'] = $status;
        $stmt3->close();


        $sql4 = "SELECT DISTINCT * FROM tbtipo_equipamentos";
        $stmt4 = $conn->prepare($sql4);
        $stmt4->execute();
        $result4 = $stmt4->get_result();


        $tipo_equipamento = array();

        while ($row4 = $result4->fetch_assoc()) {
            $IDTipo = $row4["IDTipo"];
            $descricao = $row4["descricao"];
            $tipo_equipamento[$IDTipo] = $descricao;
        }

        $resultArray['tipo_equipamento'] = $tipo_equipamento;
        $stmt4->close();

        $sql5 = "SELECT DISTINCT * FROM tbsetor";
        $stmt5 = $conn->prepare($sql5);
        $stmt5->execute();
        $result5 = $stmt5->get_result();

        $setor = array();

        while ($row5 = $result5->fetch_assoc()) {
            $IDSetor = $row5["IDSetor"];
            $descricao_setor = $row5["descricao_setor"];
            $setor[$IDSetor] = $descricao_setor;
        }

        $resultArray['setor'] = $setor;
        $stmt5->close();

        $conn->close();
        // Retornar o resultado como JSON
        echo json_encode($resultArray);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['filtroDataAbertura'])) {
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        // Recupera os dados do formulário
        $filtroDataAbertura = $_POST['filtroDataAbertura'] ?? null;
        $filtroDataFechamento = $_POST['filtroDataFechamento'] ?? null;
        $filtroResponsavel = $_POST['filtroResponsavel'] ?? null;
        $filtroAutor = $_POST['filtroAutor'] ?? null;
        $filtroStatus = $_POST['filtroStatus'] ?? null;
        $filtroTipoEquipamento = $_POST['filtroTipoEquipamento'] ?? null;
        $filtroSetor = $_POST['filtroSetor'] ?? null;
        $ordenarPor = $_POST['ordenar'] ?? null;
        $ordem = $_POST['ordem'] ?? null;
        $agruparPor = $_POST['agruparPor'] ?? null;
        $filtroATEDataAbertura = $_POST['filtroATEDataAbertura'] ?? null;
        $filtroATEDataFechamento = $_POST['filtroATEDataFechamento'] ?? null;
        $tipoRelatorio = $_POST['tipoRelatorio'] ?? null;

        $sql = "SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, c.dataFechamento, 
                sc.descricao as 'status_chamado', a.nome as 'responsavel', u.nome as 'autor', 
                e.descricao as 'equipamento', c.imagem, c.categoria, te.descricao as 'TipoEquipamento', st.descricao_setor as 'setor'
                FROM TBChamados c
                LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus
                LEFT JOIN TBUsuario a ON c.responsavel = a.IDUsuario
                LEFT JOIN TBUsuario u ON c.autor = u.IDUsuario    
                LEFT JOIN TBEquipamentos e ON c.equipamento = e.sti_ID
                LEFT JOIN TBPessoa p on c.autor = p.idpessoa
                LEFT JOIN TBTipo_equipamentos as te on e.tipo = te.IDTipo
                LEFT JOIN TBSetor st on p.setor = st.IDSetor
                WHERE 1=1";

        if (!empty($filtroDataAbertura)) {
            if ($filtroATEDataAbertura == '') {
                $sql .= " AND dataAbertura >= '$filtroDataAbertura'";
            } else {
                $filtroAberturaATEFinal = date('Y-m-d H:i:s', strtotime($filtroATEDataAbertura . ' +1 day'));
                $sql .= " AND dataAbertura BETWEEN '$filtroDataAbertura' AND '$filtroAberturaATEFinal'";
            }
        }

        if (!empty($filtroDataFechamento)) {
            if ($filtroATEDataFechamento == '') {
                // Adiciona um dia à data final para incluir também a data selecionada
                $dataFinal = date('Y-m-d H:i:s', strtotime($filtroDataFechamento . ' +1 day'));
                $sql .= " AND dataFechamento <= '$dataFinal'";
            } else {
                $filtroFechamentoATEFinal = date('Y-m-d H:i:s', strtotime($filtroATEDataFechamento . ' +1 day'));
                $sql .= " AND dataFechamento BETWEEN '$filtroDataFechamento' AND '$filtroFechamentoATEFinal'";
            }
        }

        if (!empty($filtroResponsavel)) {
            $sql .= " AND responsavel = '$filtroResponsavel'";
        }

        if (!empty($filtroAutor)) {
            $sql .= " AND autor = '$filtroAutor'";
        }

        if (!empty($filtroStatus)) {
            if ($filtroStatus != 5) {
                $sql .= " AND status_chamado = '$filtroStatus'";
            }
        }
        if (!empty($filtroTipoEquipamento)) {
            $sql .= " AND tipo = '$filtroTipoEquipamento'";
        }

        if (!empty($filtroSetor)) {
            $sql .= " AND setor = '$filtroSetor'";
        }

        if (!empty($agruparPor)) {
            $sql .= " GROUP BY $agruparPor";
        }
        // Ordenação
        if (!empty($ordenarPor)) {
            $sql .= " ORDER BY $ordenarPor";
            if ($ordem === 'decrescente') {
                $sql .= " DESC";
            }
        }
        if (!isset($_POST['gerarRelatorio'])) {
            //criterio acima é para aplicar o limit 10, apenas se nao vier o parametro gerarRelatorio
            $sql .= ' limit 10';
        }

        $dados = array();
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $IDChamado = $row['IDChamado'];
                    $assunto = $row['assunto'];
                    $descricao = $row['descricao'];
                    $dataAbertura = convertData($row['dataAbertura']);
                    $dataFechamento = convertData($row['dataFechamento']);
                    $responsavel = $row['responsavel'];
                    $equipamento = $row['equipamento'];
                    $autor = $row['autor'];
                    $status_chamado = $row['status_chamado'];
                    $tipo_equipamento = $row['TipoEquipamento'];
                    $setor = $row['setor'];


                    if (isset($_POST['gerarRelatorio'])) {
                        $teste = 0;
                        while ($teste <= 0) {
                            if (isset($_POST['geraXLSX'])) {
                                $dataAbertura = convertDataXLSX($row['dataAbertura']);
                                $dataFechamento = convertDataXLSX($row['dataFechamento']);
                            }else{
                                $dataAbertura = convertData($row['dataAbertura']);
                                $dataFechamento = convertData($row['dataFechamento']);
                            }

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
                            $teste++;
                        }
                    } else {
                        echo "<tr>
                            <td>$IDChamado</td>
                            <td>$assunto</td>
                            <td>$dataAbertura</td>
                            <td>$dataFechamento</td>
                            <td>$responsavel</td>
                            <td>$equipamento</td>
                            <td>$autor</td>
                            <td>$status_chamado</td>
                            </tr>";
                    }
                }
            } else {
                echo 1; // Não há resultados
            }
        } else {
            echo "Ocorreu um erro na preparação da consulta do SQL.";
            return;
        }
        if (isset($_POST['geraPDF'])) {
            require('tcpdf/tcpdf.php');

            // ----------------------------------------------------------------------- \\
//                             PDF DOCUMENTATION:                          \\
// ----------------------------------------------------------------------- \\
//  Cell(largura,altura,texto,borda,quebra de linha,alinhamento,fill,link) \\

            // ------------------ FUNCTIONS --------------------- \\
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

            // -------------------- GLOBAL ---------------------- \\
            $font = 'times';
            $border = 0;

            function limitaCaracteres($dado)
            {
                if (strlen($dado) > 19) {
                    return substr($dado, 0, 19) . '...';
                } else {
                    return $dado;
                }
            }
            function underline($pdf)
            {
                $pdf->Ln();
                $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY(), array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0, 0, 0)));
            }

            // ---------------------- MAIN ---------------------- \\

            $pdf = new TCPDF();
            $pdf->SetAutoPageBreak(true, 10);
            $pdf->AddPage();
            $pdf->SetFont('times', 'r', 12);

            $pdf->Cell(0, 10, date('d/m/Y'), 0, 1, 'R');
            // $pdf->setPageOrientation('L'); //modo paisagem
            $pdf->SetFont($font, 'B', 16);
            $pdf->Cell(0, 10, 'Relatório de Chamados', 0, 1, 'C');
            $pdf->SetFont($font, '', 12);
            $pdf->Ln();

            // ------------------ CONFIGURAÇÕES ------------------ \\
            // L-> largura da célula
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

            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER); // Defina a margem do rodapé
            $pdf->setFooterMargin(20);
            $pdf->setFooterFont(array($font, '', 10));

            // --------------------------------------------------- \\

            // $pdf->Ln();
            $pdf->SetFont($font, 'I', 12);

            $pdf->Cell(0, 10, 'Filtros selecionados:', 0, 1, 'C');

            if (!empty($_POST['filtroATEDataAbertura'])) {
                $pdf->Cell($pdf->GetStringWidth('Data de Abertura: DD/MM/YY'), 10, 'Data de Abertura de: ' . convertData($filtroDataAbertura), 0, 0, 'C');
                $pdf->Cell($pdf->GetStringWidth('até: DD/MM/'), 10, 'até: ' . convertData($filtroATEDataAbertura), 0, 1, 'C');
            } elseif (!empty($_POST['filtroDataAbertura'])) {
                $pdf->Cell($pdf->GetStringWidth('Data de Abertura: DD/MM/YYYY à part'), 10, 'Data de Abertura à partir de: ' . convertData($filtroDataAbertura), 0, 1, 'C');
            }

            if (!empty($_POST['filtroATEDataFechamento'])) {
                $pdf->Cell($pdf->GetStringWidth('Data de Fechamento: DD/MM/YY'), 10, 'Data de Fechamento de: ' . convertData($filtroDataFechamento), 0, 0, 'C');
                $pdf->Cell($pdf->GetStringWidth('até: DD/MM/'), 10, 'até: ' . convertData($filtroATEDataFechamento), 0, 1, 'C');
            } elseif (!empty($_POST['filtroDataFechamento'])) {
                $pdf->Cell($pdf->GetStringWidth('Data de Fechamento: ate: DD/MM'), 10, 'Data de Fechamento até: ' . convertData($filtroDataFechamento), 0, 1, 'C');
            }
            if ($filtroStatus == 5) {
                $status_chamado = "Todos";
            }
            if (!empty($_POST['filtroStatus'])) {
                $pdf->Cell($pdf->GetStringWidth('Status:XXX') + 5, 10, 'Status: ' . $status_chamado, 0, 0, 'C');
            }

            if (!empty($_POST['filtroResponsavel'])) {
                $pdf->Cell($pdf->GetStringWidth('Responsável: X') + 20, 10, 'Responsável: ' . $responsavel, 0, 0, 'C');
            }

            if (!empty($_POST['filtroTipoEquipamento'])) {
                $pdf->Cell($pdf->GetStringWidth('Equipamento: X') + 20, 10, 'Tipo: ' . $tipo_equipamento, 0, 0, 'C');
            }

            if (!empty($_POST['filtroAutor'])) {
                $pdf->Cell($pdf->GetStringWidth('Autor: X') + 23, 10, 'Autor: ' . $autor, 0, 0, 'C');
            }
            if (!empty($_POST['filtroSetor'])) {
                $pdf->Cell($pdf->GetStringWidth('Setor: X') + 23, 10, 'Setor: ' . $setor, 0, 0, 'C');
            }
            $pdf->SetFont($font, '', 12);
            $pdf->Ln();
            underline($pdf);

            $pdf->Cell($LID, 10, 'ID', $border, 0, 'C');
            $pdf->Cell($Lassunto, 10, 'Assunto', $border, 0, 'C');
            // $pdf->MultiCell($Lassunto, 10, 'Assunto', $border, 'C');
            $pdf->Cell($LdataAbertura, 10, 'Data Abertura', $border, 0, 'C');
            $pdf->Cell($LdataFechamento, 10, 'Data Fechamento', $border, 0, 'C');
            $pdf->Cell($Lresponsavel, 10, 'Responsável', $border, 0, 'C');
            $pdf->Cell($Lequipamento, 10, 'Equipamento', $border, 0, 'C');
            $pdf->Cell($Lautor, 10, 'autor', $border, 0, 'C');
            $pdf->Cell($Lstatus, 10, 'status', $border, 0, 'C');

            $pdf->Ln();
            $pdf->SetFont($font, '', 10);
            if ($tipoRelatorio == 'sintetico') {

                $i = 0;
                $tamanhoMax = 37;
                foreach ($dados as $indice => $registro) {
                    $registro['assunto'] = limitaCaracteres(($registro['assunto']));
                    $pdf->Cell($LID, $alturaResults, $registro['IDChamado'], $border, 0, 'C');
                    $pdf->Cell($Lassunto, $alturaResults, $registro['assunto'], $border, 0);
                    $pdf->Cell($LdataAbertura, $alturaResults, $registro['dataAbertura'], $border, 0, 'C');
                    $pdf->Cell($LdataFechamento, $alturaResults, $registro['dataFechamento'], $border, 0, 'C');
                    $pdf->Cell($Lresponsavel, $alturaResults, $registro['responsavel'] ? $registro['responsavel'] : '--', $border, 0, 'C');
                    $pdf->Cell($Lequipamento, $alturaResults, $registro['equipamento'] ? $registro['equipamento'] : '--', $border, 0, 'C');
                    $pdf->Cell($Lautor, $alturaResults, $registro['autor'], $border, 0, 'C');
                    $pdf->Cell($Lstatus, $alturaResults, $registro['status_chamado'], $border, 0, 'C');
                    $pdf->Ln();
                    $i++;

                    if ($i >= $tamanhoMax) {
                        // Adicionar rodapé e nova página
                        // $pdf->Ln();
                        // $pdf->Line(10, 270, 200, 270);
                        // $pdf->Cell(0, 0, 'Página ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
                        $pdf->AddPage();
                        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY(), array('width' => 0.3, 'dash' => 1));

                        $pdf->Cell($LID, 10, 'ID', $border, 0, 'C');
                        $pdf->Cell($Lassunto, 10, 'Assunto', $border, 0, 'C');
                        // $pdf->MultiCell($Lassunto, 10, 'Assunto', $border, 'C');
                        // $pdf->Cell($Ldescricao, 10, 'descricao', $border, 0, 'C');
                        $pdf->Cell($LdataAbertura, 10, 'Data Abertura', $border, 0, 'C');
                        $pdf->Cell($LdataFechamento, 10, 'Data Fechamento', $border, 0, 'C');
                        $pdf->Cell($Lresponsavel, 10, 'Responsável', $border, 0, 'C');
                        $pdf->Cell($Lequipamento, 10, 'Equipamento', $border, 0, 'C');
                        $pdf->Cell($Lautor, 10, 'autor', $border, 0, 'C');
                        $pdf->Cell($Lstatus, 10, 'status', $border, 0, 'C');

                        $pdf->Ln();
                        $pdf->SetFont($font, '', 10);

                        $i = 0;
                        $tamanhoMax = 49;
                    }
                }
            } else if ($tipoRelatorio == 'analitico') {
                $pdf->Cell(0, 10, 'Nothing Yet...', $border, 0, 'C');
            }

            //resultados:

            underline($pdf);
            $pdf->Cell(0, 10, 'Resultados:', 0, 1, 'C');
            $pdf->Cell(30, 10, 'Total de registros: ' . count($dados), $border, 0, 'C');
            $pdf->Cell(30, 10, 'Abertos: ' . count(array_filter($dados, function ($registro) {
                return $registro['status_chamado'] == 'Aberto';
            })), $border, 0, 'C');
            $pdf->Cell(30, 10, 'Fechados: ' . count(array_filter($dados, function ($registro) {
                return $registro['status_chamado'] == 'Fechado';
            })), $border, 0, 'C');
            $pdf->Cell(30, 10, 'Em andamento: ' . count(array_filter($dados, function ($registro) {
                return $registro['status_chamado'] == 'Andamento';
            })), $border, 0, 'C');
            $pdf->Cell(30, 10, 'Pendente: ' . count(array_filter($dados, function ($registro) {
                return $registro['status_chamado'] == 'Pendente';
            })), $border, 0, 'C');

            $pdf->Ln();

            $pdf->Ln();

            //  Cell(largura,altura,texto,borda,quebra de linha,alinhamento,fill,link) \\

            $pdf->Output('relatorio.pdf', 'I');
        } else
            if (isset($_POST['geraXLSX'])) {

                // $colunas = array('IDChamado','Assunto','Data Abertura','Data Fechado','Status','Responsável','Equipamento','Autor');
                $colunas = array();
                $nomeArquivo = 'relatorio.xlsx';
                $data = $dados;

                $response = array(
                    'data' => $data,
                    'colunas' => $colunas,
                    'nomeArquivo' => $nomeArquivo
                );

                echo json_encode($response);
            }

        // imprimeDados($dados);

        $stmt->close();
        $conn->close();
    }



}

?>