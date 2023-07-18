<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // var_dump($_POST);
    if(isset($_POST['select'])){
        // echo "entrou aqui";
        $sql = "SELECT * FROM tbtipo_equipamentos";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDTipo = $row["IDTipo"];
                $descricao = $row["descricao"];

                echo "<tr>
                <td>".$IDTipo."</td>
                <td>".$descricao."</td>
                <td><h5 class='bi bi-trash' onclick='deletarTipo(". $IDTipo .")'></h5></td>
                </tr>";
            }
        }
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['insert'])){

        $descricao = $_POST['digitado'];

        $sql = "SET @UltimoIDTipo = (SELECT MAX(IDTipo) FROM tbtipo_equipamentos);
        SET @UltimoIDTipo = IFNULL(@UltimoIDTipo, 0) + 1;
        INSERT INTO tbtipo_equipamentos (IDTipo, descricao)
        VALUES (@UltimoIDTipo, '$descricao');";        
        if (mysqli_multi_query($conn, $sql)) {
            echo "Nova seção adicionada com sucesso!";
        } else {
            echo "Erro ao adicionar nova seção: " . mysqli_error($conn);
        }

        $conn->close();
    }
} 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['delete'])){
        $IDTipo = $_POST['IDTipo'];

        $sql = "DELETE FROM tbtipo_equipamentos WHERE IDTipo = '$IDTipo'";

        if ($conn->query($sql) === TRUE) {
            echo "Tipo de equipamento deletado com sucesso!";
        } else {
            echo "Erro ao deletar tipo de equipamento: " . $conn->error;
        }

        $conn->close();
    }
}
?>