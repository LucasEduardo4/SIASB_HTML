<?php
if (!isset($_SESSION))
    session_start();

if (isset($_POST['teste'])) {
    $_SESSION['username'] = 'root';
    var_dump($_SESSION);
    echo "<br><br>";
}
function getOlderValues($conn, $tabela, $id, $colName)
{
    //obtendo old_values
    if ($tabela == 'localsetorsecao') {
        $sql2 = "SELECT localsetorsecao.ID, tblocal.descricao as 'Local', setorSecaoID FROM $tabela
                LEFT JOIN tblocal ON localsetorsecao.localID = tblocal.ID
                WHERE $colName = $id";
    } else {
        $sql2 = "SELECT * FROM $tabela WHERE $colName = $id";
    }

    $result = $conn->query($sql2);
    $row = $result->fetch_assoc();
    $oldValues = "";
    foreach ($row as $key => $value) {
        $oldValues .= $key . ": " . $value . "; ";
    }
    $result->close();
    return $oldValues;
}
function getNewerValues($conn, $tabela, $id, $colName)
{
    //obtendo new_values
    if ($tabela == 'localsetorsecao') {
        $sql3 = "SELECT localsetorsecao.ID, tblocal.descricao as 'Local', setorSecaoID FROM $tabela
                LEFT JOIN tblocal ON localsetorsecao.localID = tblocal.ID
                WHERE $colName = $id";
    } else {
        $sql3 = "SELECT * FROM $tabela WHERE $colName = $id";
    }
    // echo $sql3;
    $result = $conn->query($sql3);

    if ($result === false) {
        die("Erro na consulta SQL: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Resto do seu código aqui
        $newValues = "";
        foreach ($row as $key => $value) {
            $newValues .= $key . ": " . $value . "; ";
        }
        $result->close();
        return $newValues;
    } else {
        echo "Nenhum resultado encontrado.";
        $result->close();
        return "";
    }
}
function logChanges($tabela, $oldValues, $id, $newValues)
{
    $conn = mysqli_connect("localhost", "root", "", "siasb");

    $sql1 = "SELECT IDUsuario FROM tbusuario WHERE nome = '" . $_SESSION['username'] . "'";
    $result = $conn->query($sql1);
    $row = $result->fetch_assoc();
    $usuario = $row["IDUsuario"];

    if ($newValues != "" && strcmp($newValues, $oldValues) !== 0) {
        $query = "INSERT INTO tblogs_sistema (usuario, tabela, old_value, new_value, dataHora) 
    VALUES (?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($conn, $query);
        if ($stmt === false) {
            die("Preparação da consulta falhou: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssss", $usuario, $tabela, $oldValues, $newValues);
        if (mysqli_stmt_execute($stmt)) {
        } else {
            echo "Erro ao registrar alteração: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(array("success" => false, "message" => "Resultados iguais, log desprezado!"));
    }
}
?>