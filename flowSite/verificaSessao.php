<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if($_POST['verify'] == '1'){

        if(!isset($_SESSION['username'])){
            echo 'false';
        }else{
            echo 'true';
        }
        
    } 
}
?>