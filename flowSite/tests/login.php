<?php
session_start();

if(isset($_POST['username']) && isset($_POST['password'])){
    if ($_POST['username'] == 'teste' && $_POST['password'] == '123'){
        // echo $_POST['username'];
        // echo $_POST['password'];
        $_SESSION['username'] = $_POST['username'];
        header('Location: teste.php');
    }else{
        header('Location: login.html?wrongAnswer=true');
    }
}


?>