<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "siasb");
    if (isset($_POST['insertLocal'])) {
        $nomeLocal = $_POST['nomeLocal'];
        $endereco = $_POST['endereco'];

        $sqLastID = 'SELECT MAX(ID) AS ultimoID FROM tblocal';
        $resultado = $conn->query($sqLastID);
        $linha = $resultado->fetch_assoc();
        $ultimoID = $linha['ultimoID'];
        if ($ultimoID === null) {
            $ultimoID = 1;
        } else {
            $ultimoID++;
        }
        echo $ultimoID;

        $sql = 'INSERT INTO tblocal (ID, descricao, endereco, ativo) VALUES (?, ?, ?, 1)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $ultimoID, $nomeLocal, $endereco);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }
    if (isset($_POST['selectLocal'])) {
        $conn = new mysqli("localhost", "root", "", "siasb");
        $sql = 'SELECT * FROM tblocal';
        $result = $conn->query($sql);

        $dados = array();

        while ($row = $result->fetch_assoc()) {
            $ID = $row['ID'];
            $descricao = $row['descricao'];
            $endereco = $row['endereco'];
            $ativo = $row['ativo'];

            $dados[] = array('ID' => $ID, 'descricao' => $descricao, 'endereco' => $endereco, 'ativo' => $ativo);

        }
        $conn->close();
        echo json_encode($dados);
    }
    if (isset($_POST['updateLocal'])) {
        var_dump($_POST);
        // var data = "updateLocal=1&ID=" + id + "&descricao=" + encodeURIComponent(descricao) + "&endereco=" + encodeURIComponent(endereco) + "&ativo=" + ativo;
        if (isset($_POST['ID']) && isset($_POST['descricao']) && isset($_POST['endereco']) && isset($_POST['ativo'])) {
            $ID = $_POST['ID'];
            $descricao = $_POST['descricao'];
            $endereco = $_POST['endereco'];
            $ativo = $_POST['ativo'];

            $sql = "UPDATE tblocal SET descricao = '$descricao', endereco = '$endereco', ativo = $ativo WHERE ID = $ID";

            $conn->query($sql);
            $conn->close();
        } else if (isset($_POST['ID']) && isset($_POST['ativo'])) {
            $ID = $_POST['ID'];
            $ativo = $_POST['ativo'];
            $sql = "UPDATE tblocal SET ativo = $ativo WHERE ID = $ID";
            // echo $sql;
            $conn->query($sql);
            $conn->close();
        }

    }
    if (isset($_POST['deleteLocal'])) {
        // var data = "deleteLocal=1&ID=" + ID;
        $ID = $_POST['ID'];
        $sql = "DELETE FROM tblocal WHERE ID = $ID";
        $conn->query($sql);
        if ($conn->affected_rows > 0) {
            echo "true";
        } else
            echo "false";
        $conn->close();

    }
}
?>