<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "<h1>Permissão Negada!, efetue login no sistema para obter acesso</h1>";
} else {
    $username = $_SESSION['username'];
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

    function convertDataXLSX($data)
    {
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

            // $stmt1->close();
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

            $sql5 = "SELECT DISTINCT * FROM tbsetor_secao";
            $stmt5 = $conn->prepare($sql5);
            $stmt5->execute();
            $result5 = $stmt5->get_result();

            $setor = array();

            while ($row5 = $result5->fetch_assoc()) {
                $IDSetor = $row5["ID"];
                $descricao_setor = $row5["descricao"];
                $setor[$IDSetor] = $descricao_setor;
            }

            $resultArray['setor'] = $setor;
            $stmt5->close();

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

            $sql = "SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, c.dataFechamento, sc.descricao as 'status_chamado', a.nome as 'responsavel', u.nome as 'autor', e.descricao as 'equipamento', te.descricao as 'TipoEquipamento', st.descricao as 'setor', st.ID, COUNT(lc.IDLog) as 'qtdLogs'
            FROM TBChamados c 
            LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus 
            LEFT JOIN TBUsuario a ON c.responsavel = a.IDUsuario 
            LEFT JOIN TBUsuario u ON c.autor = u.IDUsuario 
            LEFT JOIN TBEquipamentos e ON c.equipamento = e.sti_ID 
            LEFT JOIN TBPessoa p on c.autor = p.idpessoa 
            LEFT JOIN TBTipo_equipamentos as te on e.tipo = te.IDTipo 
            LEFT JOIN tbsetor_secao st on p.setor_secao = st.ID
            LEFT JOIN tblog_chamado lc on lc.referencia = c.IDChamado
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
                $sql .= " AND ID = '$filtroSetor'";
            }
            $sql .= " GROUP BY c.IDChamado";

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
            // echo $sql;
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
                        $dataAbertura = $row['dataAbertura'];
                        $dataFechamento = $row['dataFechamento'];
                        $responsavel = $row['responsavel'];
                        $equipamento = $row['equipamento'];
                        $autor = $row['autor'];
                        $status_chamado = $row['status_chamado'];
                        $tipo_equipamento = $row['TipoEquipamento'];
                        $setor = $row['setor'];
                        $qtdLogs = $row['qtdLogs'];


                        if (isset($_POST['gerarRelatorio'])) {
                            $teste = 0;
                            while ($teste <= 0) {
                                if (isset($_POST['geraXLSX'])) {
                                    $dataAbertura = convertDataXLSX($row['dataAbertura']);
                                    $dataFechamento = convertDataXLSX($row['dataFechamento']);
                                } else {
                                    $dataAbertura = $row['dataAbertura'];
                                    $dataFechamento = $row['dataFechamento'];
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
                                    'autor' => $autor,
                                    'qtdLogs' => $qtdLogs,
                                );
                                $teste++;
                            }
                        } else {
                            echo "<tr>
                            <td>$IDChamado</td>
                            <td>$assunto</td>
                            <td>$dataAbertura</td>
                            <td>$dataFechamento</td>
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
                echo "Ocorreu um erro na preparação da consulta.";
                echo '<br>';
                echo $sql;
                return;
            }
            $stmt->close();
            //encontrar onde está quebrando o codigo.
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
                function underline($pdf, $tipoRelatorio)
                {
                    if ($tipoRelatorio == 'sintetico') {

                        $pdf->Ln();
                        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY(), array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0, 0, 0)));
                    } else
                        if ($tipoRelatorio == 'analitico') {
                            $pdf->Ln();
                            $pdf->Line(10, $pdf->GetY(), 280, $pdf->GetY(), array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                        }
                }



                // ---------------------- MAIN ---------------------- \\

                $pdf = new TCPDF();
                $pdf->SetAutoPageBreak(true, 10);
                $pdf->setPrintHeader(FALSE);
                $pdf->AddPage();
                $pdf->SetFont('times', 'r', 12);

                if ($tipoRelatorio == 'sintetico') {

                    $pdf->SetFont($font, 'B', 14);
                    $tamanhoImagemPrincipal = 50;
                    $tamanhoImagem = 16;
                    $pdf->Image('../Icones Site/logo-saeeb.png', 10, 15, $tamanhoImagemPrincipal, (0.32 * $tamanhoImagemPrincipal), 'png');
                    $pdf->Image('../icons/S_SIASB.png', 185, 15, $tamanhoImagem, $tamanhoImagem, 'png');
                    $pdf->Ln();
                    $pdf->Cell(0, 10, '', 0, 1, 'C');
                    $pdf->Cell(0, 10, '', 0, 1, 'C');
                    $pdf->Cell(0, 10, 'Relatório de Chamados', 0, 1, 'C');
                    $pdf->SetFont($font, '', 12);
                    $pdf->Ln();

                    // -------------- CONFIGURAÇÕES SINTETICO -------------- \\
                    // L-> largura da célula
                    $LID = $pdf->GetStringWidth('ID') + 5;
                    $Lassunto = $pdf->GetStringWidth('Assunto') + 15;
                    // $Ldescricao = $pdf->GetStringWidth('Descrição do chamado') + 9;
                    $LdataAbertura = $pdf->GetStringWidth('Data Abertura') + 2;
                    $LdataFechamento = $pdf->GetStringWidth('Data Fecha') + 2;
                    $Lresponsavel = $pdf->GetStringWidth('responsavel') + 7;
                    $Lequipamento = $pdf->GetStringWidth('Equipamento') + 10;
                    $Lautor = $pdf->GetStringWidth('Autor') + 12;
                    $Lstatus = $pdf->GetStringWidth('Status') + 13;
                    $alturaResults = 5;

                    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER); // Defina a margem do rodapé
                    $pdf->setFooterMargin(5);
                    $pdf->setFooterFont(array($font, '', 10));

                    // --------------------------------------------------- \\
                    //para agora: usuario que gerou e data gerada.
                    // $pdf->Ln();
                } else if ($tipoRelatorio == 'analitico') {

                    $pdf->setPageOrientation('L'); //modo paisagem
                    $pdf->SetFont($font, 'B', 14);
                    $tamanhoImagemPrincipal = 50;
                    $tamanhoImagem = 18;
                    $pdf->Image('../Icones Site/logo-saeeb.png', 10, 15, $tamanhoImagemPrincipal, (0.32 * $tamanhoImagemPrincipal), 'png');
                    $pdf->Image('../icons/S_SIASB.png', 265, 15, $tamanhoImagem, $tamanhoImagem, 'png');
                    $pdf->Ln();
                    $pdf->Cell(0, 10, 'Relatório de Chamados', 0, 1, 'C');
                    $pdf->SetFont($font, '', 12);
                    $pdf->Ln();

                    // -------------- CONFIGURAÇÕES ANALITICO -------------- \\
                    // L-> largura da célula
                    $LID = $pdf->GetStringWidth('ID') + 5.0;
                    $Lassunto = $pdf->GetStringWidth('Assunto') + 15.0;
                    $Ldescricao = $pdf->GetStringWidth('Descrição do chamado') + 9.0;
                    $LdataAbertura = $pdf->GetStringWidth('Data Abertura') + 2.0;
                    $LdataFechamento = $pdf->GetStringWidth('Data Fecha') + 2.0;
                    $Lresponsavel = $pdf->GetStringWidth('responsavel') + 7.0;
                    $Lequipamento = $pdf->GetStringWidth('Equipamento') + 10.0;
                    $Lautor = $pdf->GetStringWidth('Autor') + 12.0;
                    $Lstatus = $pdf->GetStringWidth('Status') + 13.0;
                    $alturaResults = 5.0;

                    // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER); // Defina a margem do rodapé
                    $pdf->setFooterMargin(5);
                    $pdf->setFooterFont(array($font, '', 10));

                    // --------------------------------------------------- \\
                }
                $pdf->SetFont($font, 'I', 12);
                //-----------------------------------------VERIFICAÇÃO DOS FILTROS-----------------------------------------\\
                $dadosFiltros = array();

                if (!empty($_POST['filtroATEDataAbertura'])) {
                    $dadosFiltros['Data de Abertura'] = 'de: ' . $filtroDataAbertura . ' até: ' . $filtroATEDataAbertura;
                } elseif (!empty($_POST['filtroDataAbertura'])) {
                    $dadosFiltros['Data de Abertura'] = 'a partir de: ' . $filtroDataAbertura;
                }

                if (!empty($_POST['filtroATEDataFechamento'])) {
                    $dadosFiltros['Data de Fechamento'] = 'de: ' . $filtroDataFechamento . ' até: ' . $filtroATEDataFechamento;
                } elseif (!empty($_POST['filtroDataFechamento'])) {
                    $dadosFiltros['Data de Fechamento'] = 'até: ' . $filtroDataFechamento;
                }

                if ($filtroStatus == 5) {
                    $status_chamado = "Todos";
                }
                if (!empty($_POST['filtroStatus'])) {
                    $dadosFiltros['Status'] = $status_chamado;
                }

                if (!empty($_POST['filtroResponsavel'])) {
                    $dadosFiltros['Responsável'] = $responsavel;
                }

                if (!empty($_POST['filtroTipoEquipamento'])) {
                    $dadosFiltros['Tipo'] = $tipo_equipamento;
                }

                if (!empty($_POST['filtroAutor'])) {
                    $dadosFiltros['Autor'] = $autor;
                }

                if (!empty($_POST['filtroSetor'])) {
                    $dadosFiltros['Setor'] = $setor;
                }

                if (!empty($_POST['agruparPor'])) {
                    $dadosFiltros['Agrupar por'] = $_POST['agruparPor'];
                }

                if (!empty($_POST['ordenar'])) {
                    $dadosFiltros['Ordenar por'] = $_POST['ordenar'];
                }

                // Bloco abaixo é para adicionar dinamicamente as informações no relatório
                $pdf->Cell(0, 10, 'Filtros selecionados para geração do relatório:', 0, 1, 'C');
                $soma = 0;
                foreach ($dadosFiltros as $filtro => $valor) {
                    $tamanho = intval($pdf->GetStringWidth($filtro . ': ' . $valor));
                    $soma += $tamanho;
                    if ($tipoRelatorio == 'sintetico')
                        $width = 160;
                    else if ($tipoRelatorio == 'analitico')
                        $width = 230;

                    if ($soma > $width) {
                        $pdf->Ln(); // Adiciona uma nova linha antes da próxima célula
                        $soma = $tamanho; // Redefine a soma para o tamanho da célula atual
                    }

                    $pdf->Cell($tamanho + 10, 10, $filtro . ': ' . $valor, 0, 0, 'L');
                }

                //------------------------------------------------------------------------------------------------------------------\\

                $pdf->SetFont($font, '', 12);
                $pdf->Ln();
                $pdf->Cell(0, 10, 'Gerado em: ' . date('d/m/Y'), 0, 0, 'R');


                if ($tipoRelatorio == 'sintetico') {
                    underline($pdf, $tipoRelatorio);

                    $pdf->Cell($LID, 10, 'ID', $border, 0, 'C');
                    $pdf->Cell($Lassunto, 10, 'Assunto', $border, 0, 'L');
                    // $pdf->MultiCell($Lassunto, 10, 'Assunto', $border, 'C');
                    $pdf->Cell($LdataAbertura, 10, 'Abertura', $border, 0, 'C');
                    $pdf->Cell($LdataFechamento, 10, 'Fechamento', $border, 0, 'C');
                    $pdf->Cell($Lresponsavel, 10, 'Responsável', $border, 0, 'C');
                    $pdf->Cell($Lequipamento, 10, 'Equipamento', $border, 0, 'C');
                    $pdf->Cell($Lautor, 10, 'autor', $border, 0, 'C');
                    $pdf->Cell($Lstatus, 10, 'status', $border, 0, 'C');

                    $pdf->Ln();
                    $pdf->SetFont($font, '', 10);

                    $i = 0;
                    $tamanhoMax = 33;
                    foreach ($dados as $indice => $registro) {
                        $registro['assunto'] = limitaCaracteres(($registro['assunto']));
                        $pdf->Cell($LID, $alturaResults, $registro['IDChamado'], $border, 0, 'C');
                        $pdf->Cell($Lassunto, $alturaResults, $registro['assunto'], $border, 0);
                        $pdf->Cell($LdataAbertura, $alturaResults, convertData($registro['dataAbertura']), $border, 0, 'C');
                        $pdf->Cell($LdataFechamento, $alturaResults, convertData($registro['dataFechamento']), $border, 0, 'C');
                        $pdf->Cell($Lresponsavel, $alturaResults, $registro['responsavel'] ? $registro['responsavel'] : '--', $border, 0, 'C');
                        $pdf->Cell($Lequipamento, $alturaResults, $registro['equipamento'] ? $registro['equipamento'] : '--', $border, 0, 'C');
                        $pdf->Cell($Lautor, $alturaResults, $registro['autor'], $border, 0, 'C');
                        $pdf->Cell($Lstatus, $alturaResults, $registro['status_chamado'], $border, 0, 'C');
                        $pdf->Ln();
                        $i++;

                        if ($i >= $tamanhoMax) {
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
                            $tamanhoMax = 51;
                        }
                    }

                } else if ($tipoRelatorio == 'analitico') {
                    $minBottomMargin = 20;

                    foreach ($dados as $indice => $registro) {
                        $descricaoCampo = $registro['descricao'];
                        $qtdLogs = $registro['qtdLogs'];
                        $currentY = $pdf->GetY();
                        if (strlen($descricaoCampo) <= 200 && $qtdLogs <= 1)
                            $blockHeight = 40; //estimativa altura bloco atual
                        else if (strlen($descricaoCampo) >= 200 && $qtdLogs <= 1)
                            $blockHeight = 60;
                        else if (strlen($descricaoCampo) >= 200 && $qtdLogs > 1)
                            $blockHeight = 60 + ($qtdLogs);
                        else if (strlen($descricaoCampo) <= 200 && $qtdLogs > 1)
                            $blockHeight = 70 + ($qtdLogs);


                        if ($currentY + $blockHeight + $minBottomMargin > $pdf->getPageHeight()) {
                            // Caso não haja espaço:
                            $pdf->AddPage();
                            $pdf->SetFont($font, 'B', 14);
                            $tamanhoImagemPrincipal = 50;
                            $pdf->Image('../Icones Site/logo-saeeb.png', 10, 15, $tamanhoImagemPrincipal, (0.32 * $tamanhoImagemPrincipal), 'png');
                            $pdf->Image('../icons/S_SIASB.png', 265, 15, $tamanhoImagem, $tamanhoImagem, 'png');
                            $pdf->Ln();
                            $pdf->Cell(0, 10, 'Relatório de Chamados', 0, 1, 'C');
                            $pdf->SetFont($font, '', 12);
                            $pdf->Ln();
                        }
                        underline($pdf, $tipoRelatorio);

                        $pdf->Ln(2);
                        $pdf->MultiCell(8, 10, 'ID:', $border, 'L', false, 0);
                        $pdf->MultiCell(25, 10, $registro['IDChamado'], $border, 'L', false, 0);
                        $pdf->MultiCell(18, 10, 'Assunto:', $border, 'R', false, 0);
                        $pdf->MultiCell(94, 10, $registro['assunto'], $border, 'L', false, 0);
                        $pdf->MultiCell(30, 10, 'Autor:', $border, 'R', false, 0);
                        $pdf->MultiCell(40, 10, $registro['autor'], $border, 'L', false, 0);
                        $pdf->MultiCell(24, 10, 'Status:', $border, 'R', false, 0);
                        $pdf->MultiCell(23, 10, $registro['status_chamado'], $border, 'L', false, 1);
                        $pdf->Ln(1);
                        $pdf->MultiCell(25, 10, 'Descrição: ', $border, 'L', false, 0);
                        $pdf->MultiCell(120, 10, $descricaoCampo, $border, 'L', false, 0);
                        $pdf->MultiCell(30, 10, 'Equipamento:', $border, 'R', false, 0);
                        $pdf->MultiCell(40, 10, $registro['equipamento'] ? $registro['equipamento'] : '-----', $border, 'L', false, 1); //$DATAABERTURA
                        if (strlen($descricaoCampo) > 65) {
                            $tamanho = strlen($descricaoCampo);
                            $result = $tamanho / 65;
                            $w = ($result * 4);
                            $pdf->Ln($w);
                        }

                        $pdf->MultiCell(33, 10, 'Data de Abertura:', $border, 'R', false, 0);
                        $pdf->MultiCell(39, 10, convertData($registro['dataAbertura']), $border, 'L', false, 0);

                        if ($registro['status_chamado'] == 'Fechado') {
                            $pdf->MultiCell(39.05, 10, 'Data de Fechamento:', $border, 'R', false, 0);
                            $pdf->MultiCell(39, 10, $registro['dataFechamento'] ? convertData($registro['dataFechamento']) : '----', $border, 'L', false, 0);
                        }

                        if ($qtdLogs != 0) {
                            $pdf->MultiCell(50, 10, 'Última alteração no status:', $border, 'L', false, 0);
                            $pdf->MultiCell(50, 10, $registro['responsavel'] ? $registro['responsavel'] : '----', $border, 'L', false, $registro['status_chamado'] == 'Fechado' ? 1 : 0);
                            $pdf->SetMargins(80, 0, 0, true);
                        }

                        // calcular dias em aberto
                        if ($registro['status_chamado'] == 'Fechado') {
                            $dataAbertura = strtotime($registro['dataAbertura']);
                            $dataFechamento = strtotime($registro['dataFechamento']);
                            $diasEmAberto = round(($dataFechamento - $dataAbertura) / 86400);

                            // exibir dias em aberto
                            $pdf->MultiCell(30, 10, 'Dias em aberto:', $border, 'R', false, 0);
                            $pdf->MultiCell(40, 10, $diasEmAberto, $border, 'L', false, 0);

                        }
                        $sql = "SELECT c.dataAlteracao, c.mensagem, u.nome, s.descricao FROM tblog_chamado c
                        LEFT JOIN tbusuario u on c.responsavel = u.IDUsuario
                        LEFT JOIN tbstatus_chamado s on c.status = s.IDStatus
                         WHERE referencia = " . $registro['IDChamado'];
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $logs = array();
                        if ($qtdLogs != 0)
                            $pdf->MultiCell(200, 10, 'Logs de Alterações:', $border, 'L', false, 1);
                        $isFirstRow = true;
                        $maiorLinhaMsg = 40; //LTRB
                        $maiorLinhaUser = 10;
                        while ($row2 = $result->fetch_assoc()) {
                            if ($pdf->GetStringWidth($row2['mensagem']) + 10 > $maiorLinhaMsg)
                                $maiorLinhaMsg = $pdf->GetStringWidth($row2['mensagem']) + 20;
                            if ($pdf->GetStringWidth($row2['nome']) + 12 > $maiorLinhaUser)
                                $maiorLinhaUser = $pdf->GetStringWidth($row2['nome']) + 12;
                            if ($isFirstRow) {
                                $pdf->SetFont($font, 'U', 14);
                                $border =  array(
                                    'T' => array('width' => 0.5),  // Borda superior
                                    'B' => array('width' => 0.5),  // Borda inferior
                                );
                                // $border = 1;
                                $pdf->Cell($maiorLinhaUser, 10, 'Usuário', $border, 0, 'C');
                                $pdf->Cell(25, 10, 'Data', $border, 0, 'C');
                                $pdf->Cell(30, 10, 'Status', $border, 0, 'C');
                                $pdf->Cell($maiorLinhaMsg, 10, 'Mensagem', $border, 1, 'C');
                                $pdf->SetFont($font, '', 12);
                            }

                            $pdf->Cell($maiorLinhaUser, 10, $row2['nome'] , $border, 0, 'C');
                            $pdf->Cell(25, 10, convertData($row2['dataAlteracao']), $border, 0, 'C');
                            $pdf->Cell(30, 10, $row2['descricao'], $border, 0, 'C');
                            $pdf->Cell($maiorLinhaMsg, 10, $row2['mensagem'], $border, 1, 'C');
                            $isFirstRow = false;

                        }
                        $border = 0;
                        $pdf->SetMargins(10, 0, 0, true);

                        $stmt->close();
                        $pdf->Ln(2);
                    }
                }

                //resultados:
                if ($pdf->GetY() + 60 + 10 > $pdf->getPageHeight()) {
                    $pdf->SetMargins(10, 0, 0, true);
                    $pdf->AddPage();

                    $pdf->SetFont($font, 'B', 14);
                    $tamanhoImagemPrincipal = 50;
                    $pdf->Image('../Icones Site/logo-saeeb.png', 10, 15, $tamanhoImagemPrincipal, (0.32 * $tamanhoImagemPrincipal), 'png');
                    $pdf->Image('../icons/S_SIASB.png', 265, 15, $tamanhoImagem, $tamanhoImagem, 'png');
                    $pdf->Ln();
                    $pdf->Cell(0, 10, 'Relatório de Chamados', 0, 1, 'C');
                    $pdf->SetFont($font, '', 12);

                    underline($pdf, $tipoRelatorio);
                } else
                    underline($pdf, $tipoRelatorio);

                $pdf->Cell(0, 10, 'Resultados:', 0, 1, 'C');
                $pdf->Cell(52, 10, 'Total de registros: ' . count($dados), $border, 0, 'R');
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
                $pdf->Cell(0, 10, 'Relatório gerado por: ' . $username . ' em ' . date('d/m/Y'), 0, 0, 'C');

                $pdf->Ln();

                //  Cell(largura,altura,texto,borda,quebra de linha,alinhamento,fill,link) \\
                $pdf->SetTitle('Relatório Gerado!');
                $pdf->Output('/Relatorio Chamados ' . date('d-m-Y') . '.pdf', 'I'); // Gera o PDF
                exit();
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
        }
    }
    $conn->close();

}
?>