<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // var_dump($_POST);
    if (isset($_POST['select'])) {
        // echo "entrou aqui";
        $sql = "SELECT * FROM tbtipo_equipamentos";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDTipo = $row["IDTipo"];
                $descricao = $row["descricao"];
                $ID = $IDTipo;

                echo "<tr>
                <td id='id-$ID'>" . $IDTipo . "</td>
                <td id='desc-$ID'>" . $descricao . "</td>
                <td><span class='bi bi-pencil' onclick='action(`edit-{$ID}`)' id='edit-{$ID}'></span>
                <span class='bi bi-trash' onclick='action(`trash-{$ID}`)' id='trash-{$ID}'></span></td>
                </tr>";
            }
        }
    } else
        if (isset($_POST['insert'])) {

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
        } else
            if (isset($_POST['delete'])) {
                $IDTipo = $_POST['ID'];

                $sql = "DELETE FROM tbtipo_equipamentos WHERE IDTipo = '$IDTipo'";

                if ($conn->query($sql) === TRUE) {
                    echo "true";
                } else {
                    echo "Erro ao deletar tipo de equipamento: " . $conn->error;
                }

                $conn->close();
            } else
                if (isset($_POST['updateTipoEquip'])) {
                    $IDTipo = $_POST['ID'];
                    $descricao = $_POST['descricao'];

                    $sql = "UPDATE tbtipo_equipamentos SET descricao = '$descricao' WHERE IDTipo = '$IDTipo'";

                    if ($conn->query($sql) === TRUE) {
                        echo "Tipo de equipamento atualizado com sucesso!";
                    } else {
                        echo "Erro ao atualizar tipo de equipamento: " . $conn->error;
                    }

                    $conn->close();
                }
}
?>