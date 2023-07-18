<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['Select_Tipo_Secao'])){
        $sql = "SELECT * FROM TBTipo";

        $resultArray = array();

        // Consulta 1: SELECT distinct IDPessoa, nomeCompleto FROM TBPessoa WHERE gestor = 1
        $sql1 = "SELECT distinct IDTipo, descricao
                 FROM tbtipo_equipamentos";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        $tipos = array();
        while ($row1 = $result1->fetch_assoc()) {
            $IDTipo = $row1["IDTipo"];
            $descricao = $row1["descricao"];
            $tipos[$IDTipo] = $descricao;
        }
        $resultArray['tipos'] = $tipos;

        // Consulta 2: SELECT * FROM TBSetor
        $sql2 = "SELECT * FROM TBSecao";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $secoes = array();
        while ($row2 = $result2->fetch_assoc()) {
            $IDSecao = $row2["IDSecao"];
            $descricaoSecao = $row2["descricao_secao"];
            $secoes[$IDSecao] = $descricaoSecao;
        }
        $resultArray['secoes'] = $secoes;

        $stmt1->close();
        $stmt2->close();
        $conn->close();

        // Retornar o resultado como JSON
        echo json_encode($resultArray);
    }
} 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['Select_Person_on_secao'])){
        $secao = $_POST['Select_Person_on_secao'];
        $sql = "SELECT nomeCompleto, IDPessoa FROM TBPessoa WHERE secao ='$secao'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDPessoa = $row["IDPessoa"];
                $nomeCompleto = $row["nomeCompleto"];

                echo "<option value='$IDPessoa'>$nomeCompleto</option>";
            }
        }
        
    }
}
?>