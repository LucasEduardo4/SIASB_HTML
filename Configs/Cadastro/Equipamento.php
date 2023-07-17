<?php
$conn = mysqli_connect("localhost", "root", "", "siasb");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if($_POST['Select_Tipo'] == 1){
        $sql = "SELECT * FROM TBTipo";
    }
}

?>