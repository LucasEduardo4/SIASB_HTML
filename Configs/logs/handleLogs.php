<?php
require_once("../../model/conexao.php");
if (isset($_POST['tabela']))
    $tabela = $_POST['tabela'];
else if (isset($_POST['tabela1'])) {
    $tabela1 = $_POST['tabela1'];
    $tabela2 = $_POST['tabela2'];
}

function transformToJSON($input)
{
    $result = [];
    $splits = explode("; ", $input);

    foreach ($splits as $split) {
        $pair = explode(": ", $split);
        if (count($pair) === 2) {
            $key = trim($pair[0]);
            $value = trim($pair[1]);
            if (!empty($key) && !empty($value)) {
                $result[".$key."] = $value;
            }
        }
    }
    return $result;
}

if (isset($_POST['tabela'])) {
    if ($tabela == 'tbstatus_chamado') {
        function getLogs($conn, $tabela)
        {
            $sql = "SELECT IDLog, u.nome as 'usuario', old_value, new_value, dataHora FROM tblogs_sistema 
        LEFT JOIN tbusuario u on tblogs_sistema.usuario = u.IDUsuario
        WHERE tabela = '$tabela'
        ORDER BY dataHora ASC";
            $result = mysqli_query($conn, $sql);
            $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);

            foreach ($logs as $log) {
                $id = $log['IDLog'];
                $usuario = $log['usuario'];
                $old_value = $log['old_value'];
                $new_value = $log['new_value'];
                $dataHora = $log['dataHora'];
                $old_value = transformToJSON($old_value);
                $new_value = transformToJSON($new_value);

                $dados[] = array('IDLog' => $id, 'usuario' => $usuario, 'old_value' => $old_value, 'new_value' => $new_value, 'dataHora' => $dataHora);
            }
            if (isset($dados)) {
                return json_encode($dados);
            } else {
                return "Nenhum Log";
            }
        }

        echo getLogs($conn, $tabela);
    } else
        if ($tabela == 'tblocal') {
            function getLogs($conn, $tabela)
            {
                $sql = "SELECT IDLog, u.nome as 'usuario', old_value, new_value, dataHora FROM tblogs_sistema 
        LEFT JOIN tbusuario u on tblogs_sistema.usuario = u.IDUsuario
        WHERE tabela = '$tabela'
        ORDER BY dataHora ASC";
                $result = mysqli_query($conn, $sql);
                $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);

                foreach ($logs as $log) {
                    $id = $log['IDLog'];
                    $usuario = $log['usuario'];
                    $old_value = $log['old_value'];
                    $new_value = $log['new_value'];
                    $dataHora = $log['dataHora'];
                    $old_value = transformToJSON($old_value);
                    $new_value = transformToJSON($new_value);

                    $dados[] = array('IDLog' => $id, 'usuario' => $usuario, 'old_value' => $old_value, 'new_value' => $new_value, 'dataHora' => $dataHora);
                }
                if (isset($dados)) {
                    return json_encode($dados);
                } else {
                    return "Nenhum Log";
                }
            }

            echo getLogs($conn, $tabela);
        } else
            if ($tabela == 'tbtipo_equipamentos') {
                function getLogs($conn, $tabela)
                {
                    $sql = "SELECT IDLog, u.nome as 'usuario', old_value, new_value, dataHora FROM tblogs_sistema 
                    LEFT JOIN tbusuario u on tblogs_sistema.usuario = u.IDUsuario
                    WHERE tabela = '$tabela'
                    ORDER BY dataHora ASC";
                    $result = mysqli_query($conn, $sql);
                    $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    foreach ($logs as $log) {
                        $id = $log['IDLog'];
                        $usuario = $log['usuario'];
                        $old_value = $log['old_value'];
                        $new_value = $log['new_value'];
                        $dataHora = $log['dataHora'];
                        $old_value = transformToJSON($old_value);
                        $new_value = transformToJSON($new_value);

                        $dados[] = array('IDLog' => $id, 'usuario' => $usuario, 'old_value' => $old_value, 'new_value' => $new_value, 'dataHora' => $dataHora);
                    }
                    if (isset($dados)) {
                        return json_encode($dados);
                    } else {
                        return "Nenhum Log";
                    }
                }

                echo getLogs($conn, $tabela);
            }
} else if (isset($_POST['tabela1']) && isset($_POST['tabela2'])) {
    if ($tabela1 == 'tbsetor_secao' && $tabela2 == 'localsetorsecao') {
        function getLogs($conn, $tabela)
        {
            $sql = "SELECT IDLog, u.nome as 'usuario', old_value, new_value, dataHora FROM tblogs_sistema 
                    LEFT JOIN tbusuario u on tblogs_sistema.usuario = u.IDUsuario
                    WHERE tabela = '$tabela'
                    ORDER BY dataHora ASC";
            $result = mysqli_query($conn, $sql);
            $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);

            foreach ($logs as $log) {
                $id = $log['IDLog'];
                $usuario = $log['usuario'];
                $old_value = $log['old_value'];
                $new_value = $log['new_value'];
                $dataHora = $log['dataHora'];
                $old_value = transformToJSON($old_value);
                $new_value = transformToJSON($new_value);

                $dados[] = array('IDLog' => $id, 'usuario' => $usuario, 'old_value' => $old_value, 'new_value' => $new_value, 'dataHora' => $dataHora);
            }
            if (isset($dados)) {
                return json_encode($dados);
            } else {
                return "Nenhum Log";
            }
        }
        $result1 = getLogs($conn, $tabela1);
        $result2 = getLogs($conn, $tabela2);

        // Decodificar os resultados em arrays PHP
        $array1 = json_decode($result1, true);
        $array2 = json_decode($result2, true);

        // Combinar os arrays em um único array
        $combinedArray = array_merge($array1, $array2);

        // Converter o resultado combinado em JSON
        $combinedResults = json_encode($combinedArray);

        echo $combinedResults;
    }
}




?>