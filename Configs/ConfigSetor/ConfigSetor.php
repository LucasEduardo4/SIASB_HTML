<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");
$connMulti = mysqli_connect("localhost", "root", "", "siasb");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["select_ready"])) {
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $sql = "SELECT DISTINCT sc.ID, sc.descricao,  l.descricao as 'local', l.ID as 'localID' from tbsetor_secao sc
                LEFT JOIN localsetorsecao lsc on sc.ID = lsc.setorSecaoID
                LEFT JOIN tblocal l on lsc.localID = l.ID
                ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ID = $row["ID"];
                $descricao = $row["descricao"];
                $local = $row["local"];
                $localID = $row["localID"];


                echo "<tr>
                <td id='id-$ID'>" . $ID . "</td>
                <td id='desc-$ID'>" . $descricao . "</td>
                <td id='local-$ID' value='$localID'>" . $local . "</td>
                <td><span class='bi bi-pencil' onclick='action(`edit-{$ID}`)' id='edit-{$ID}'></span>
                <span class='bi bi-trash' onclick='action(`trash-{$ID}`)' id='trash-{$ID}'></span></td>
                </tr>";
            }
        }

        $stmt->close();
        $conn->close();
    } else
        if (isset($_POST["consultaLocal"]) && !isset($_POST["nomeSetor"])) {

            $sql2 = "SELECT * FROM tblocal";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $locais = array();

            if ($result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
                    $ID = $row["ID"];
                    $descricao = $row["descricao"];

                    $locais[] = array("ID" => $ID, "descricao" => $descricao);
                }
            }
            $resultados = array();
            $resultados[] = array("locais" => $locais);
            echo json_encode($resultados);
            $stmt2->close();
            $conn->close();
        } else
            if (isset($_POST["nomeSetor"]) && isset($_POST["local"])) {
                $nomeSetor = $_POST["nomeSetor"];
                $local = $_POST["local"];
                if ($conn->connect_error) {
                    die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
                }

                $sql = "
                    SET @UltimoID = (SELECT MAX(ID) FROM tbsetor_secao);
                    SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
                    INSERT INTO tbsetor_secao (ID, descricao, ativo)
                    VALUES (@UltimoID, '$nomeSetor', 1);
                    INSERT INTO localsetorsecao(localID, setorSecaoID) VALUES($local, @UltimoID);
                    ";

                if (mysqli_multi_query($conn, $sql)) {
                    echo "Novo setor adicionado com sucesso!";
                } else {
                    echo "Erro ao adicionar novo status: " . mysqli_error($conn);
                }

                $conn->close();
            } else
                if (isset($_POST['deleteSetor'])) {

                    $ID = $_POST['ID'];

                    if ($conn->connect_error) {
                        die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
                    }

                    $sql = "START TRANSACTION;
                    DELETE FROM localsetorsecao where setorsecaoid = $ID;
                    DELETE FROM tbsetor_secao WHERE ID = $ID;
                    COMMIT";

                    if (mysqli_multi_query($conn, $sql)) {
                        echo "true";
                    } else {
                        echo "Erro ao deletar setor: " . mysqli_error($conn);
                    }

                    $conn->close();
                } else
                    if (isset($_POST['updateSetor'])) {
                        require_once "../logs/functions.php";

                        $ID = $_POST['ID'];
                        $descricao = $_POST['descricao'];
                        $local = $_POST['local'];

                        if ($conn->connect_error) {
                            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
                        }

                        $oldValues = getOlderValues($conn, 'tbsetor_secao', $ID, "ID");
                        $oldValues2 = getOlderValues($conn, "localsetorsecao", $ID, "setorSecaoID");
                        
                        $sql = "UPDATE tbsetor_secao SET descricao = '$descricao' WHERE ID = $ID;
                        UPDATE localsetorsecao SET localID = $local WHERE setorSecaoID = $ID;";

                        // echo $sql;

                        if (mysqli_multi_query($connMulti, $sql)) {
                            echo "true";
                        } else {
                            echo "Erro ao atualizar setor: " . mysqli_error($conn);
                        }

                        $newValues = getNewerValues($conn, 'tbsetor_secao', $ID, "ID");
                        $newValues2 = getNewerValues($conn, "localsetorsecao", $ID, "setorSecaoID");
                        echo "<br><br>";
                        echo "oldValues: <br>";
                        var_dump($oldValues2);
                        echo "<br><br>";
                        echo "newValues: <br>";
                        var_dump($newValues2);
                        echo "<br><br>";
                        echo "LogChanges: <br>";
                        logChanges('tbsetor_secao', $oldValues, $ID, $newValues);
                        logChanges('localsetorsecao', $oldValues2, $ID, $newValues2);

                        $conn->close();
                    }
}
?>