<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["select_ready"])) {
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $sql = "SELECT DISTINCT sc.ID, sc.descricao, p.nomeCompleto, p.IDPessoa as 'responsavelID', l.descricao as 'local', l.ID as 'localID' from tbsetor_secao sc
        LEFT JOIN TBPessoa p on gerente = p.IDPessoa
        LEFT JOIN localsetorsecao lsc on sc.ID = lsc.setorSecaoID
        LEFT JOIN tblocal l on lsc.localID = l.ID";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ID = $row["ID"];
                $descricao = $row["descricao"];
                $gerente = $row["nomeCompleto"];
                $local = $row["local"];
                $localID = $row["localID"];
                $responsavelID = $row["responsavelID"];


                echo "<tr>
                <td id='id-$ID'>" . $ID . "</td>
                <td id='desc-$ID'>" . $descricao . "</td>
                <td id='gerente-$ID' value='$responsavelID'>" . $gerente . "</td>
                <td id='local-$ID' value='$localID'>" . $local . "</td>
                <td><span class='bi bi-pencil' onclick='action(`edit-{$ID}`)' id='edit-{$ID}'></span>
                <span class='bi bi-trash' onclick='action(`trash-{$ID}`)' id='trash-{$ID}'></span></td>
                </tr>";
            }
        }

        $stmt->close();
        $conn->close();
    } else
        if (isset($_POST["gerente"])) {
            if ($conn->connect_error) {
                die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
            }

            $sql = "SELECT distinct IDPessoa, nomeCompleto
                    from TBPessoa
                    where gestor =1";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $pessoas = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $IDPessoa = $row["IDPessoa"];
                    $gerente = $row["nomeCompleto"];

                    // echo"<option disabled selected hidden>-------------------------</option>
                    // <option value='".$IDPessoa."'>".$gerente."</option>";
                    $pessoas[] = array("IDPessoa" => $IDPessoa, "gerente" => $gerente);
                }
            }
            $stmt->close();
            
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
            $resultados[] = array("pessoas" => $pessoas, "locais" => $locais);
            echo json_encode($resultados);
            $stmt2->close();
            $conn->close();
        } else
            if (isset($_POST["nomeSetor"]) && isset($_POST["responsavel"])  && isset($_POST["local"])){
                $nomeSetor = $_POST["nomeSetor"];
                $gerente = $_POST["responsavel"];
                $local = $_POST["local"];
                if ($conn->connect_error) {
                    die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
                }

                $sql = "
                    SET @UltimoID = (SELECT MAX(ID) FROM tbsetor_secao);
                    SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
                    INSERT INTO tbsetor_secao (ID, descricao, gerente, ativo)
                    VALUES (@UltimoID, '$nomeSetor', '$gerente', 1);
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
                        echo 'at least';
                        $ID = $_POST['ID'];
                        $descricao = $_POST['descricao'];
                        $gerente = $_POST['responsavel'];
                        $local = $_POST['local'];

                        if ($conn->connect_error) {
                            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
                        }

                        $sql = "UPDATE tbsetor_secao SET descricao = '$descricao', gerente = '$gerente' WHERE ID = $ID;
                        UPDATE localsetorsecao SET localID = $local WHERE setorSecaoID = $ID;";
                        echo $sql;
                        if (mysqli_multi_query($conn, $sql)) {
                            echo "true";
                        } else {
                            echo "Erro ao atualizar setor: " . mysqli_error($conn);
                        }

                        $conn->close();
                    }
}
?>