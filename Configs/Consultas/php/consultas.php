<?php
require_once("../../../model/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["select_ready_person"])) {
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        $sql = "SELECT p.IDPessoa, p.nomeCompleto, p.cpf, p.matricula, sc.descricao, p.email, u.IDUsuario, u.administrador, u.habilitado
                FROM TBPessoa p
                JOIN tbsetor_secao sc ON p.setor_secao = sc.ID
                LEFT JOIN TBUsuario	u on p.IDPessoa = u.IDUsuario
                ORDER BY p.IDPessoa ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $IDPessoa = $row["IDPessoa"];
                $nomeCompleto = $row["nomeCompleto"];
                $cpf = $row["cpf"];
                $matricula = $row["matricula"];
                $setor_secao = $row["descricao"];
                $email = $row['email'];
                $IDUsuario = $row['IDUsuario'];
                $administrador = $row['administrador'];
                $habilitado = $row['habilitado'];
             
                if ($IDUsuario) {
                    $userType = "<h3 onclick=verificaUsuario(this) id='usuarioComum' title='Usuário comum' class='bi bi-person-fill  my-custom-icon'></h3>";
                    if ($administrador) {
                        $userType = "<h3 onclick=verificaUsuario(this) id='usuarioAdministrador' title='Usuário Administrador' class='bi bi-person-fill-up  my-custom-icon'></h3>";
                    }
                } else {
                    $userType = "<h3 onclick=verificaUsuario(this) ' id='usuarioNaoCadastrado' title='Não cadastrado como usuário' class='bi bi-person-slash'></h3>";
                }
                if ($habilitado == 0 && $IDUsuario) {
                    $userType = "<h3 onclick=verificaUsuario(this) ' id='usuarioDesabilitado' title='Usuário Desabilitado' class='bi bi-person-fill-slash'></h3>";

                } //icone de desabilitado: <i class="bi bi-person-fill-slash"></i>
                echo "<tr>
                <td>" . $IDPessoa . "</td>
                <td id='nomeCompleto'>" . $nomeCompleto . "</td>
                <td id='CPF'>" . $cpf . "</td>
                <td>" . $matricula . "</td>
                <td>" . $setor_secao . "</td>
                <td>" . $email . "</td>
                <td id=" . $IDPessoa . ">" . $userType . "</td>
                </tr>";
            }
        }

        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["verifica"])) {
        // $conn = mysqli_connect("localhost", "root", "", "siasb");
        $verifica = $_POST['verifica'];
        $id = $_POST['id'];

        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }
        // $sql = "UPDATE tbstatus_chamado SET descricao = '$editedValue' WHERE IDStatus = '$id'";
        
        if ($conn->query($sql) === TRUE) {
            // Atualização bem-sucedida
            echo json_encode(array("success" => true, "message" => "Status atualizado com sucesso!"));
        } else {
            // Erro ao executar a consulta SQL
            echo json_encode(array("success" => false, "message" => "Erro ao atualizar o status: " . $conn->error));
        }

        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['select_ready_equipment'])) {

        $sql = "
        SELECT e.sti_id, e.descricao, e.ip, te.descricao as 'tipo',p.nomeCompleto, p.IDPessoa, sc.descricao as 'setor_secao' FROM TBEquipamentos e
        join TBTipo_Equipamentos te on te.IDTipo = e.tipo
        join TBPessoa p on p.IDPessoa = e.usuario
        join tbsetor_secao sc on sc.ID = e.setor_secao
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $dataArray = array(); // Para conter todas as linhas de dados

        while ($row = $result->fetch_assoc()) {
            // Use os dados dos arrays associativos para preencher os valores correspondentes
            $sti_id = $row["sti_id"];
            $descricao = $row["descricao"];
            $ip = $row["ip"];
            $tipo = $row["tipo"];
            $nomeCompleto = $row["nomeCompleto"];
            $IDPessoa = $row["IDPessoa"];
            $setor_secao = $row["setor_secao"];

            // Construa um array associativo para esta linha de dados
            $equipamentos = array(
                "sti_id" => $sti_id,
                "descricao" => $descricao,
                "ip" => $ip,
                "tipo" => $tipo,
                "nomeCompleto" => $nomeCompleto,
                "IDPessoa" => $IDPessoa,
                "secao" => $setor_secao
            );

            // Adicione o array associativo da linha de dados ao array de dados
            $dataArray[] = $equipamentos;
        }

        // Retorne os dados em formato JSON para uso futuro
        echo json_encode($dataArray);


        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selectNameUser'])) {
        $nome = $_POST['nome'];

        $sql = "SELECT * FROM TBUsuario where nome = '$nome'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $nomeCadastrado = '';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nomeCadastrado = $row['nome'];
            }
        }

        $stmt->close();
        $conn->close();

    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registerUser'])) {
        $nome = $_POST['nome'];
        $senha = $_POST['senha'];
        $idUsuario = $_POST['idUsuario'];
        $adm = $_POST['adm'];

        $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO TBUsuario (IDUsuario, nome, senha, administrador, habilitado) VALUES (?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $idUsuario, $nome, $hashed_password, $adm);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['desabilitarUsuario']) || isset($_POST['habilitarUsuario'])) {
        $idUsuario = $_POST['ID_User'];

        if ($_POST['desabilitarUsuario'] == 1) {
            $sql = "UPDATE TBUsuario SET habilitado = 0 WHERE IDUsuario = '$idUsuario'";
        }
        if ($_POST['habilitarUsuario'] == 1) {
            $sql = "UPDATE TBUsuario SET habilitado = 1 WHERE IDUsuario = '$idUsuario'";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionarPrivilegio']) || isset($_POST['retirarPrivilegio'])) {
        $idUsuario = $_POST['ID_User'];

        if ($_POST['adicionarPrivilegio'] == 1) {
            $sql = "UPDATE TBUsuario SET administrador = 1 WHERE IDUsuario = '$idUsuario'";
        }
        if ($_POST['retirarPrivilegio'] == 1) {
            $sql = "UPDATE TBUsuario SET administrador = 0 WHERE IDUsuario = '$idUsuario'";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>