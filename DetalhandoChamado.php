<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['ChamadoID'])){
        $conn = mysqli_connect("localhost", "root", "", "siasb");

        $IDChamado = $_POST['ChamadoID'];

        $sql = "SELECT * FROM tbchamados WHERE IDChamado = $IDChamado"; // fazer os joins, pra retornar os nomes ao inves dos ID's.

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            if($result->num_rows > 0){
                while ($row = $result->fetch_assoc()) {
                    $resultArray = array();
                    // $resultArray['index'] = $variavel;
                    $IDChamado = $row["IDChamado"];
                    $assunto = $row["assunto"];
                    $descricao = $row["descricao"];
                    $dataAbertura = $row["dataAbertura"];
                    $status_chamado = $row["status_chamado"];
                    $responsavel = $row["responsavel"];
                    $autor = $row["autor"];
                    $equipamento = $row["equipamento"];
                    $imagem = $row["imagem"];
                    $categoria = $row["categoria"];
                    
                    $resultArray['IDChamado'] = $IDChamado;
                    $resultArray['assunto'] = $assunto;
                    $resultArray['descricao'] = $descricao;
                    $resultArray['dataAbertura'] = $dataAbertura;
                    $resultArray['status_chamado'] = $status_chamado;
                    $resultArray['responsavel'] = $responsavel;
                    $resultArray['autor'] = $autor;
                    $resultArray['equipamento'] = $equipamento;
                    $resultArray['imagem'] = $imagem;
                    $resultArray['categoria'] = $categoria;
                }
                    
                    echo json_encode($resultArray);
                    
            }
        }
    }
}
?>