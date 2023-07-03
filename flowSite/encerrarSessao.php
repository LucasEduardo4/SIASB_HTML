<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['shutdown'] == '1'){
    session_start();        
        if(isset($_SESSION['username'])){
            echo 'true';
            session_destroy();
        }
        
    } 
}
?>