<?php
$tabela = $_POST['tabela'];

$conn = mysqli_connect("localhost", "root", "", "siasb");
function getLogs($conn, $tabela)
{
    $sql = "SELECT * FROM tblogs_sistema WHERE tabela = '$tabela'";
    $result = mysqli_query($conn, $sql);
    $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
    return json_encode($dados);
}

echo getLogs($conn, $tabela);

?>