<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Vertical Menu Overlay</title>

  <style>
body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  overflow: hidden;
}

.menu-container {
  position: fixed;
  top: 0;
  right: 0;
  width: 10%;
  height: 100%;
  background-color: #333;
  transition: width 0.3s ease;
  overflow: hidden;
}

.menu-expanded {
  width: 20%;
}

.menu-hidden {
  display: none;
  padding: 20px;
}

.menu-container:hover {
  width: 20%;
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

/* =============================================== */
</style>



</head>
<body>
<div class="menu-container" id="menu">
    <div class="menu-hidden" id="menuHidden">
      <a href="#">Home</a>
      <a href="#">Configurações</a>
      <a href="#">Painel</a>
    </div>

    <div class="teste">
      <a href="#">teste</a>
      <a href="#">teste</a>
      <a href="#">teste</a>
    </div>

  </div>
  <!-- <script>
    const menuOverlay = document.querySelector('.menu-overlay');

menuOverlay.addEventListener('mouseenter', () => {
  menuOverlay.classList.add('active');
});

menuOverlay.addEventListener('mouseleave', () => {
  menuOverlay.classList.remove('active');
});

  </script> -->
</body>
</html>
