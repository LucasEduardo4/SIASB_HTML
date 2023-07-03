<?php


if(isset($_POST['username']) && isset($_POST['password'])){
    if ($_POST['username'] == 'teste' && $_POST['password'] == '123'){
        session_start();
        header('Location: teste.php');

    }
}


?>