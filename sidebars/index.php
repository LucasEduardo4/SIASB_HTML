<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../login.html");
  exit();
}

//abaixo está a verificação se o usuário está ativo:
$conn = mysqli_connect('localhost', 'root', '', 'siasb');
$username = $_SESSION['username'];

$sql = "SELECT * FROM TBUsuario u  
      LEFT JOIN TBPessoa p on p.IDPessoa = u.IDUsuario
      WHERE u.nome = '$username'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $habilitado = $row["habilitado"];
    $setor_secao = $row["setor_secao"];

    // $resultHabilitado = $habilitado == 1 ? true : false;
    // $resultSetorSecao = $setor_secao == 1 ? true : false;

    // if($resultHabilitado && $resultSetorSecao){
    //     echo 'true';
    // }else{
    //     echo 'false';
    // }

    if ($habilitado != 1) {
      header("Location: ../flowsite/usuarioinativo.html");
    } else {
      // echo 'permitido!';
    }
  }
}
?>
<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siasb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}


$usuario_ = $_SESSION['username'];

$sql = "SELECT * FROM tbusuario WHERE nome = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // Encontrou resultados
  $row = $result->fetch_assoc();
  $Meu_ID = $row["IDUsuario"];
}

// $sql = "SELECT IDPessoa, nomeCompleto, cpf, matricula, setor, secao, email FROM tbusuario  WHERE IDUsuario = $Meu_ID";

$sql = "SELECT icone FROM tbpessoa WHERE IDPessoa = $Meu_ID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $imageData = $row['icone']; // Use 'icone' em vez de 'imagem_coluna'
} else {
  echo "Imagem não encontrada.";
  exit;
}

$conn->close();

if ($imageData) {
  // Display the image using base64 encoding
  $base64Image = base64_encode($imageData);
  // echo "<img src='data:image/jpeg;base64,$base64Image' alt='User Profile Image'>";
} else {
  echo "Image not found.";
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HOME INICIAL SIASB</title>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sidebars/">
  <link href="bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="https://saaeb.com.br/wp-content/uploads/2019/09/favicon.png" sizes="192x192" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="sidebars.css" rel="stylesheet">


  <script src="bootstrap.bundle.min.js"></script>
  <script src="sidebars.js"></script><!-- Code injected by live-server -->
  <!-- <script src="../assets/js/color-modes.js"></script> -->

  <style>
    /* Estilo para o Tema Claro */
    .light-theme {
      background-color: #fff;
      color: #000;
    }

    .light-theme .table-heading th,
    .light-theme input[type="text"],
    .light-theme button {
      background-color: #00cc99;
      color: #fff;
      border-color: #00cc99;
    }

    /* Estilo para o Tema Escuro */
    .dark-theme {
      background-color: #121212;
      color: #fff;
    }

    .dark-theme .table-heading th,
    .dark-theme input[type="text"],
    .dark-theme button {
      background-color: #007bff;
      color: #fff;
      border-color: #007bff;
    }


    .sidebar {
      width: 97%;
      background-color: #00a383;
      padding: 5px;
      padding-left: 20px;
      margin-left: 60px;
      position: absolute;
      padding-top: 10px;
      display: flex;
      justify-content: flex-end;
      z-index: 0;
      max
    }

    .sidebar_icon {
      /* margin-left: 95%; */
      background-color: #B3B3B3;
      right: 20px;
      border: solid 0.5px black;
      margin-top: 10px;
      position: absolute;
      display: flex;
      justify-content: flex-end;
      z-index: 0;
      border-radius: 100px;
    }

    .sidebar_icon:hover {
      cursor: pointer;
      background-color: #939393;
    }

    #notificationSign {
      margin: 10px;
      width: 35px;
      fill: gold;
    }

    .sidebar ul {
      list-style-type: none;
      padding: 0;
      display: flex;
    }

    .sidebar li {
      margin-right: 10px;
    }

    .sidebar a {
      text-decoration: none;
      color: #333;
    }

    .content {
      padding: 20px;
    }

    #imagemContainer {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      overflow: hidden;
    }

    #imagemContainer img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    body {
      background-color: #f6f6f6;
      z-index: 0;

    }

    body.claro {
      background-color: #f6f6f6;
      color: #ffffff;
    }

    body.escuro .table-heading th,
    body.escuro input[type="text"],
    body.escuro button,
    body.escuro h1 {
      background-color: #00cc99;
      color: black;
      border-color: #00cc99;
    }

    body.escuro {
      background-color: #525252;
      color: #454545;

    }

    .menu {
      list-style: none;
      padding: 0;
    }

    .dropdown {
      position: relative;
      display: inline-block;
      cursor: pointer;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #000000;
      min-width: 160px;
      padding: 10px;
      border-radius: 5%;
      color: white;
    }

    .menu {
      padding: 10px;
    }

    .menu ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
    }

    .menu-item {
      cursor: pointer;
      padding: 10px 0px;
      height: 70px;
      color: #333;
      margin-bottom: 40px;
      font-weight: bold;
      display: flex;
      align-items: center;
    }

    .menu-item li {
      height: 45px;
      display: flex;
      align-items: center;
      padding-left: 15px;
    }

    p {
      padding-left: 10px;
      margin-bottom: 0rem;
      height: 100%;
      display: flex;
      align-items: center;
    }

    p:hover {
      color: white;

    }

    .menu-item.active {
      color: white;
      font-weight: bold;
    }


    #myIframe {
      width: 100%;
    }

    .cor_ativado {
      color: #007bff;
      text-decoration: none;
      padding-left: 16px;
    }

    .cor_desativado {
      color: white;
      text-decoration: none;
      padding-left: 16px;
      cursor: pointer;

    }

    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .b-example-divider {
      width: 100%;
      height: 3rem;
      background-color: rgba(0, 0, 0, .1);
      border: solid rgba(0, 0, 0, .15);
      border-width: 1px 0;
      box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .b-example-vr {
      flex-shrink: 0;
      /* width: 1.5rem; */
      height: 100vh;
    }

    .bi {
      vertical-align: -.125em;
      fill: currentColor;
    }

    .nav-scroller {
      position: relative;
      z-index: 2;
      height: 2.75rem;
      overflow-y: hidden;
    }

    .nav-scroller .nav {
      display: flex;
      flex-wrap: nowrap;
      padding-bottom: 1rem;
      margin-top: -1px;
      overflow-x: auto;
      text-align: center;
      white-space: nowrap;
      -webkit-overflow-scrolling: touch;
    }

    .btn-bd-primary {
      --bd-violet-bg: #007bff;
      --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

      --bs-btn-font-weight: 600;
      --bs-btn-color: var(--bs-white);
      --bs-btn-bg: var(--bd-violet-bg);
      --bs-btn-border-color: var(--bd-violet-bg);
      --bs-btn-hover-color: var(--bs-white);
      --bs-btn-hover-bg: #007bff;
      --bs-btn-hover-border-color: #007bff;
      --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
      --bs-btn-active-color: var(--bs-btn-hover-color);
      --bs-btn-active-bg: #007bff;
      --bs-btn-active-border-color: #007bff;
    }

    .bd-mode-toggle {
      z-index: 1500;
    }

    #map {
      /* Obs. esse mapa é para fechar a janela de notificação ao clicar fora */
      width: 100px;
      height: 100px;
      font-size: 16px;
      position: fixed;
      top: 0px;
      left: 60px;
      width: 100%;
      height: 100%;
      display: none;
      background-color: rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease-in-out;
    }

    .circleDiv {
      position: relative;
      display: inline-block;
    }

    .circle {
      position: absolute;
      top: 38px;
      right: 40px;
      width: 15px;
      height: 15px;
      background-color: rgb(64, 163, 131);
      border-radius: 50%;
      border: solid 1px black;
      /* display: none; */
    }

    #icone {
      clip-path: circle(farthest-side);
    }

    body,
    html {
      margin: 0;
      padding: 0;
      height: 100%;

      /* overflow: hidden; */
    }

    .menu-container {
      /* position: fixed; */
      top: 0;
      right: 0;
      width: 4em;
      height: 100%;
      background-color: #333;
      transition: all 0.5s cubic-bezier(0, 0.19, 1, 1.01);
      overflow: hidden;
      z-index: 10;
    }

    .menu-hidden {
      display: none;
    }

    .menu-container:hover {
      /* transition: all 3s ease-in-out; */
      transition: all 3s cubic-bezier(0, 0.19, 1, 1.01);
      width: 17em;
      min-width: 15em;
    }

    .menu-container:hover .menu-hidden {
      display: block;
    }

    a {
      color: white;
    }

    /* REMOVENDO AS LOGOS QUANDO EXPANDIR A DIV */
    .teste {
      color: white;
    }

    .menu-container:hover .teste {
      display: none;
    }


    .perfil_img {
      position: relative;
      margin: 0;
      display: flex;
      z-index: 0;
      bottom: 20%;
      padding-left: 15px;
    }

    @media (max-width: 768px) {
      .perfil_img {
        bottom: 8%;
      }
    }

    .troca_cor {
      position: absolute;
      margin: 0;
      display: flex;
      z-index: 0;
      bottom: 3%;
      left: 0.5%;
    }
  </style>

  <!-- Custom styles for this template -->
</head>

<body cz-shortcut-listen="true" onload="abrirHome()">
  <div id="Principal"></div>
  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check2" viewBox="0 0 16 16">
      <path
        d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z">
      </path>
    </symbol>
    <symbol id="circle-half" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
    </symbol>
    <symbol id="moon-stars-fill" viewBox="0 0 16 16">
      <path
        d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z">
      </path>
      <path
        d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z">
      </path>
    </symbol>
    <symbol id="sun-fill" viewBox="0 0 16 16">
      <path
        d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z">
      </path>
    </symbol>
  </svg>


  <!-- RELIZANDO A TROCA DE TEMAS DO SITE, DE CLARO PARA ESCURO -->



  <!-- <div class="perfil_img">
    <hr>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
        data-bs-toggle="dropdown" aria-expanded="false">



        <div>
          <div id="anexosContainer"></div>
        </div>

        <script>

          var imagem = <?php echo json_encode(base64_encode($imageData)); ?>;

          if (imagem) {
            document.getElementById("anexosContainer").innerHTML +=
              '<p> <img id="icone" src="data:image/jpeg;base64,' + imagem + '" width="60" height="60" alt="" /></p>';
          } else {
            document.getElementById("anexosContainer").innerHTML +=
              '<p>Nenhuma imagem anexada para este chamado.</p>';
          }
        </script>




      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow">

        <li><a onclick="abrirPerfil()" class="dropdown-item" href="#">PERFIL</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a onclick="encerrarSessao()" class="dropdown-item" href="#">SAIR</a></li>
      </ul>
    </div>
  </div> -->
  </div>

  <!-- <header >
    <button id="theme-toggle">Alterar Tema</button>
</header> -->



  <!-- <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="bootstrap" viewBox="0 0 118 94">
      <title>Bootstrap</title>
      <path fill-rule="evenodd" clip-rule="evenodd"
        d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z">
      </path>
    </symbol>
    <symbol id="home" viewBox="0 0 16 16">
      <path
        d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z">
      </path>
    </symbol>
    <symbol id="speedometer2" viewBox="0 0 16 16">
      <path
        d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z">
      </path>
      <path fill-rule="evenodd"
        d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z">
      </path>
    </symbol>
    <symbol id="table" viewBox="0 0 16 16">
      <path
        d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z">
      </path>
    </symbol>
    <symbol id="people-circle" viewBox="0 0 16 16">
      <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
      <path fill-rule="evenodd"
        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z">
      </path>
    </symbol>
    <symbol id="grid" viewBox="0 0 16 16">
      <path
        d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z">
      </path>
    </symbol>
  </svg> -->

  <!-- CÓDIGO DA SIDEBAR SUPERIOR -->
  <div id="centerBox">
    <div id="map" title="Clique para fechar a janela de notificaçoes">
      <!-- Essa div é apenas para deiaxr um mapa na tela, para que seja possível fechar a notificação quando é clicada-->
    </div>
  </div>
  <div class="sidebar_icon" onclick="abrirNotificacao(event)" ;>
    <div class='circleDiv' id="circle">
      <img src="..\Icones Site\NOTIFICACAO.png" id="notificationSign" alt="saaeb barretos" width="40">
      <div class="circle"></div>
    </div>

  </div>

  <main class="d-flex flex-nowrap">
    <h1 class="visually-hidden">Sidebars</h1>

    <!-- POSSO REALIZAR AS ALTERAÇÕES ATRAVÉS DO DFLEX NESSE CAMPO-->
    <!-- <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 100px;"> -->

    <!-- <div style="background-color: green; padding-left:300px; padding-bottom:20px;" > -->



    <div class="menu-container" id="menu">
      <div class="menu-hidden" id="menuHidden">
        <p
          style="color:white; font-size:30px; padding-bottom:20px,font-weight: 900; padding-bottom:10px;font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;">
          SIASB</p>

        <div onclick="abrirHome()" class="menu-item">
          <li onclick="alterarCor(this)">
            <img src="..\Icones Site\HOME BRANCO.png" alt="saaeb barretos" class="menu-image" height="30">
          </li>
          <p style="color: white;"> HOME </p>
        </div>

        <div onclick="abrirChamados()" class="menu-item">
          <li onclick="alterarCor(this)">
            <img src="..\Icones Site\telefone.PNG" alt="saaeb barretos" height="30">
          </li>
          <p style="color:white"> ABRIR CHAMADO </p>
        </div>

        <!-- <div class="dropdown" onclick="abrirDropdown()"> -->
        <div onclick="abrirIframe('Ver Chamados')" class="menu-item">
          <li onclick="alterarCor(this)">
            <img src="..\Icones Site\CHAMADO BRANCO2.png" alt=" saaeb barretos" height="30">
          </li>
          <p style="color: white;"> HISTORICO DE CHAMADOS </p>
        </div>


        <!-- <div onclick="abrirSite()">
          <li style="padding-left:0px;" onclick="alterarCor(this)" class="menu-item">
            <img src="..\Icones Site\SITE BRANCO.png" alt="saaeb barretos" height="30">
            <p style="color: white;"> SITE </p>
          </li>
        </div> -->

        <div onclick="abrirConfiguracoes()" class="menu-item administrador">
          <li onclick="alterarCor(this)">
            <img src="..\Icones Site\ENGRENAGEM BRANCO.png" alt="saaeb barretos" height="30">
          </li>
          <p style="color: white;"> CONFIGURAÇÕES </p>
        </div>
        <div onclick="abrirResolvendo()" class="menu-item administrador">
          <li onclick="alterarCor(this)">
            <img src="..\Icones Site\SETA BRANCO.PNG" alt="saaeb barretos" height="30">
          </li>
          <p style="color: white; "> RESOLVER </p>
        </div>

        <div class="perfil_img">
          <hr>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
              data-bs-toggle="dropdown" aria-expanded="false">



              <div>
                <div id="anexosContainer"></div>
              </div>

              <script>

                var imagem = <?php echo json_encode(base64_encode($imageData)); ?>;

                if (imagem) {
                  document.getElementById("anexosContainer").innerHTML +=
                    '<p> <img id="icone" src="data:image/jpeg;base64,' + imagem + '" width="150" height="150" alt="" /></p>';
                } else {
                  document.getElementById("anexosContainer").innerHTML +=
                    '<p>Nenhuma imagem anexada para este chamado.</p>';
                }
              </script>


              <!-- <strong style="padding-left: 10px;color:black;"><?php echo $_SESSION['username']; ?></strong> -->




            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">

              <!-- ANTERAÇÕES DO PERFIL ESTÁ SENDO REALIZADA -->
              <li><a onclick="abrirPerfil()" class="dropdown-item" href="#">PERFIL</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a onclick="encerrarSessao()" class="dropdown-item" href="#">SAIR</a></li>
            </ul>
          </div>
        </div>



        <div class="troca_cor">
          <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button"
            aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
              <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
              <button onclick="alterarTema('claro')" id="btnTemaClaro" type="button"
                class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                  <use href="#sun-fill"></use>
                </svg>
                Claro
                <svg class="bi ms-auto d-none" width="1em" height="1em">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
            <li>
              <button onclick="alterarTema('escuro')" id="btnTemaEscuro" class="dropdown-item d-flex align-items-center"
                data-bs-theme-value="dark" aria-pressed="false">
                <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                  <use href="#moon-stars-fill"></use>
                </svg>
                Escuro
                <svg class="bi ms-auto d-none" width="1em" height="1em">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
          </ul>
        </div>

      </div>

      <div class="teste">
        <div>
          <p
            style="color:white; font-size:22px; padding-bottom:20px,font-weight: 900; padding-bottom:10px;font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;">
            &nbsp;</p> <!-- <<<<<<<< espaço pra logo aqui -->
        </div>
        <div class="menu-item">
          <li>
            <img src="..\Icones Site\HOME BRANCO.png" alt="saaeb barretos" height="22">
          </li>
        </div>
        <div class="menu-item">
          <li>
            <img src="..\Icones Site\telefone.png" alt=" saaeb barretos" height="22">
          </li>
        </div>
        <div class="menu-item">
          <li>
            <img src="..\Icones Site\CHAMADO BRANCO2.png" alt=" saaeb barretos" height="22">
          </li>
        </div>
        <div class="menu-item administrador">
          <li>
            <img src="..\Icones Site\ENGRENAGEM BRANCO.png" alt="saaeb barretos" height="22">
          </li>
        </div>
        <div class="menu-item administrador">
          <li>
            <img src="..\Icones Site\SETA BRANCO.PNG" alt="saaeb barretos" height="22">
          </li>
        </div>
      </div>
    </div>

    <script>
      function verificaADM(response) {
        var opcoes = document.getElementsByClassName("administrador");
        if (response == 1) {
          for (var i = 0; i < opcoes.length; i++) {
            opcoes[i].style.display = "flex";
          }
        } else {
          for (var i = 0; i < opcoes.length; i++) {
            opcoes[i].style.display = "none";
          }
        }

      }

    </script>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "siasb");
    $usuario = $_SESSION['username'];
    $sql = "SELECT * FROM tbusuario WHERE nome = '$usuario'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['administrador'] == '1') {
      echo "<script>verificaADM(1)</script>";
    } else {
      echo "<script>verificaADM(0)</script>";
    }

    ?>




    <!-- </div>
    <div style=" width: 60px; background-color: #00a383; padding-bottom:10px;" >
      <a >
        <img style="margin-right:30px; padding-top: 0px;" src="..\Icones Site\logo.png" alt="saaeb barretos" width="80" height="110">
      </a>

      <nav class="menu"  >
        <ul >
          <div onclick="abrirHome()">
            <li onclick="alterarCor(this)" class="menu-item">
              <img src="..\Icones Site\HOME BRANCO.png" alt="saaeb barretos" height="22">
            </li>
          </div>

          <div onclick="abrirIframe('Ver Chamados')">
            <li style="padding-left:0px;" onclick="alterarCor(this)" class="menu-item">
              <img src="..\Icones Site\CHAMADO BRANCO.png" alt=" saaeb barretos" height="22">
            </li>
          </div>

          <div onclick="abrirSite()">
            <li style="padding-left:0px;" onclick="alterarCor(this)" class="menu-item">
              <img src="..\Icones Site\SITE BRANCO.png" alt="saaeb barretos" height="22">
            </li>
          </div>

          <div onclick="abrirConfiguracoes()">
            <li onclick="alterarCor(this)" class="menu-item">
              <img src="..\Icones Site\ENGRENAGEM BRANCO.png" alt="saaeb barretos" height="22">
            </li>
          </div>
        </ul>

      </nav> -->


    <!-- REALIZANDO O OVERLAY DO MENU -->

    <!-- <script> 
    function openMenu() {
    const closedMenu = document.querySelector('.closed-menu');
    }

    function closeMenu() {
    const closedMenu = document.querySelector('.closed-menu');
    }
    </script> -->

    <!-- 
      <script>
    var menuToggle = document.getElementById('menu-toggle');
    var overlay = document.getElementById('overlay');

    menuToggle.addEventListener('mouseover', function() {
    overlay.style.left = '0';
    });

    overlay.addEventListener('mouseleave', function() {
    overlay.style.left = '-300px';
    });
      </script> -->




    <!-- TIRANDO A PARTE QUE DIVIDE O MENU DA TELA PRINCIPAL DE CONTEUDO -->
    <div class="b-example-vr"></div>
    <iframe id="myIframe" frameborder="0"></iframe>

    <script>
      var previousSrc = null;
      document.getElementById("myIframe").addEventListener("load", function () {
        var currentSrc = this.src;

        if (previousSrc !== currentSrc) {
          previousSrc = currentSrc;
          init();
        }
      });

      function init() {
        // console.log("init")
        var Frame = document.getElementById("myIframe");
        Frame.contentDocument.location.reload(true);
      }

      function verificaNovaNotificacao(nova) {
        var circle = document.getElementsByClassName("circle")[0];
        if (nova == 'true') {
          circle.style.display = 'block';
        } else
          if (nova == 'false') {
            circle.style.display = 'none';
          }
      }

      function foo(idNotificacao, nova) {
        console.log(idNotificacao);
        var split = idNotificacao.split(';');
        idNotificacao = split[1]; // Obtemos a primeira parte da string
        console.log(idNotificacao);
        var iframe = document.getElementById("myNotifications");
        iframe.hidden = true;
        var iframeContainer = document.getElementById('myIframe');
        iframeContainer.src = "../detalhandoChamado.html?IDChamado=" + idNotificacao;
        var mapa = document.getElementById("map");
        mapa.style.display = 'none'
        verificaNovaNotificacao(nova);
      }

      var iframe = document.createElement("iframe");
      iframe.src = "notificacoes.html";
      iframe.style.position = "fixed";
      iframe.style.top = "80px";
      iframe.style.right = "20px";
      // iframe.style.width = "100%";
      iframe.style.width = "370px";
      iframe.style.height = "500px";
      iframe.style.backgroundColor = "rgba(0, 0, 0, 0.0)";
      iframe.style.zIndex = "9999";
      iframe.style.position = "absolute";
      iframe.id = "myNotifications"
      iframe.hidden = true;


      document.body.appendChild(iframe);

      function abrirNotificacao(event) {
        var mapa = document.getElementById("map");
        if (event) {
          event.stopPropagation();
        }

        var iframe = document.getElementById("myNotifications");

        if (iframe.hidden) {
          iframe.hidden = false;
          mapa.style.display = "block";

          function clickListener(event) {
            // Verificar se o clique foi dentro do iframe
            var isClickedInsideIframe = iframe.contains(event.target);
            if (!isClickedInsideIframe) {
              iframe.hidden = true;
              mapa.style.display = "none";
              document.body.removeEventListener("click", clickListener);
            }
          }

          document.body.addEventListener("click", clickListener);
        } else {
          iframe.hidden = true;
        }
      }

      function fecharNotificacao(event) {
        if (event) {
          event.stopPropagation();
        }

        var iframe = document.getElementById("myNotifications");
        iframe.hidden = true;
      }

      function encerrarSessao() {

        var currentPath = window.location.pathname;
        var pathArray = currentPath.split('/');

        var basePath = '/' + pathArray[1] + '/flowSite/encerrarSessao.php'
        var xhr = new XMLHttpRequest();
        xhr.open("POST", basePath, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              if (xhr.responseText == "true") {
                window.location.href = `/${pathArray[1]}/Login.html`;
              }
            }
          }
        };
        xhr.send("shutdown=" + encodeURIComponent(1));
        console.log("Envia a req.")

      }

      function alterarCor(elemento) {
        var menuItens = document.querySelectorAll('.menu-item');

        menuItens.forEach(function (item) {
          if (item === elemento) {
            item.classList.add('active');
          } else {
            item.classList.remove('active');
          }
        });
      }

      //ABRINDO OS IFRAME PELO MENU DE OPÇÕES
      function abrirHome() {
        var iframe = document.getElementById("myIframe");
        iframe.src = "../PaginaInicial.php";
      }

      function abrirSite() {
        var iframe = document.getElementById("myIframe");
        iframe.src = "https://saaeb.com.br/";
      }

      function abrirConfiguracoes() {
        var iframe = document.getElementById("myIframe");
        iframe.src = "../Configs/index.html";
      }

      function abrirPerfil() {
        var iframe = document.getElementById("myIframe");
        iframe.src = "../Perfil.php";
      }

      function abrirResolvendo() {
        var iframe = document.getElementById("myIframe");
        iframe.src = "../ResolvendoChamados.html";
      }

      function abrirChamados() {
        var iframe = document.getElementById("myIframe");
        iframe.src = "../AbrindoChamado.html";
      }

      //ABRINDO DROPBOX NA OPÇÃO DOS CHAMADOS
      function abrirDropdown() {
        var dropdownContent = document.querySelector('.dropdown-content');
        dropdownContent.style.display = (dropdownContent.style.display === 'block') ? 'none' : 'block';
      }

      function abrirIframe(opcao) {
        var iframeContainer = document.getElementById('myIframe');
        if (opcao === 'Abrir Chamado') {
          iframeContainer.src = "../AbrindoChamado.html";
        } else if (opcao === 'Ver Chamados') {
          iframeContainer.src = "../VendoChamados.html";
        }
      }

      // REALIZANDO A ALTERAÇÃO DO TEMA DO SITE
      var temaAtual = null;

      function alterarTema(tema) {
        var body = document.body;
        var btnTemaClaro = document.getElementById("btnTemaClaro");
        var btnTemaEscuro = document.getElementById("btnTemaEscuro");

        if (tema === "claro") {
          body.classList.remove("escuro");
          body.classList.add("claro");

          btnTemaClaro.disabled = true;
          btnTemaEscuro.disabled = false;
          temaAtual = "claro";
        } else if (tema === "escuro") {
          body.classList.remove("claro");
          body.classList.add("escuro");

          btnTemaClaro.disabled = false;
          btnTemaEscuro.disabled = true;
          temaAtual = "escuro";
        }
      }

    </script>
  </main>
  <script>
    //   // <![CDATA[  <-- For SVG support
    //   if ('WebSocket' in window) {
    //     (function () {
    //       function refreshCSS() {
    //         var sheets = [].slice.call(document.getElementsByTagName("link"));
    //         var head = document.getElementsByTagName("head")[0];
    //         for (var i = 0; i < sheets.length; ++i) {
    //           var elem = sheets[i];
    //           var parent = elem.parentElement || head;
    //           parent.removeChild(elem);
    //           var rel = elem.rel;
    //           if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
    //             var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
    //             elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
    //           }
    //           parent.appendChild(elem);
    //         }
    //       }
    //       var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
    //       var address = protocol + window.location.host + window.location.pathname + '/ws';
    //       var socket = new WebSocket(address);
    //       socket.onmessage = function (msg) {
    //         if (msg.data == 'reload') window.location.reload();
    //         else if (msg.data == 'refreshcss') refreshCSS();
    //       };
    //       if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
    //         console.log('Live reload enabled.');
    //         sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
    //       }
    //     })();
    //   }
    //   else {
    //     console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
    //   }

    // // ]]>

    //   // <![CDATA[  <-- For SVG support
    //   if ('WebSocket' in window) {
    //     (function () {
    //       function refreshCSS() {
    //         var sheets = [].slice.call(document.getElementsByTagName("link"));
    //         var head = document.getElementsByTagName("head")[0];
    //         for (var i = 0; i < sheets.length; ++i) {
    //           var elem = sheets[i];
    //           var parent = elem.parentElement || head;
    //           parent.removeChild(elem);
    //           var rel = elem.rel;
    //           if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
    //             var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
    //             elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
    //           }
    //           parent.appendChild(elem);
    //         }
    //       }
    //       var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
    //       var address = protocol + window.location.host + window.location.pathname + '/ws';
    //       var socket = new WebSocket(address);
    //       socket.onmessage = function (msg) {
    //         if (msg.data == 'reload') window.location.reload();
    //         else if (msg.data == 'refreshcss') refreshCSS();
    //       };
    //       if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
    //         console.log('Live reload enabled.');
    //         sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
    //       }
    //     })();
    //   }
    //   else {
    //     console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
    //   }
    // // ]]>

    // document.addEventListener("DOMContentLoaded", function () {
    //   const themeToggle = document.getElementById("theme-toggle"); //theme-toggle está comentado.
    //   const contentFrame = document.getElementById("content-frame"); //nao existe esse contentFrame.

    //   themeToggle.addEventListener("click", function () {
    //     const frameDocument = contentFrame.contentDocument || contentFrame.contentWindow.document;
    //     frameDocument.body.classList.toggle("dark-theme");
    //   });
    // });


  </script>
</body>

</html>