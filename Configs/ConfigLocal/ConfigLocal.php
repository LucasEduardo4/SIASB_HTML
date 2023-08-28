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
    if(isset($_POST['selectLocal'])){
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
}
?>