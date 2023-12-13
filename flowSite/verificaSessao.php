<?php
session_start();
var_dump($_SESSION);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['verify'] == '1'){

        if(!isset($_SESSION['username'])){
            echo 'false';
        }else{
            echo 'true';
        }
        
    } 
}
?>