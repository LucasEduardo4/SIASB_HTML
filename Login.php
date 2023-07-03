<?php
    if(isset ($_POST['username']) && isset($_POST['password'])){
        if($_POST['username'] == 'root' && $_POST['password'] == 'toor'){
            session_start();
            $_SESSION['username'] = $_POST['username'];
            header('Location: sidebars/index.html');
        }else{
            header('Location: Login.html?error=true');
        }

    }

?>