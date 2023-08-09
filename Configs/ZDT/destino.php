<?php
    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        if(isset($_POST['funcaoTeste'])){
            echo 'executeFoo';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #teste{
            color: red;
            font-size: 40px;
            display: none;
        }
    </style>
</head>
<body>
    <div id="teste">Funcionou!!!</div>
    <iframe src="remetente.html" frameborder="1"></iframe>
</body>
<script>
    function foo(){
        document.getElementById("teste").style.display = "block";
    }
</script>
</html>