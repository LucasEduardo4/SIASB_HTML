<!DOCTYPE html>
<html>
<head>
    <title>Minha Página com Sidebar</title>
    <!-- Link para o arquivo de estilos CSS -->
    <link rel="stylesheet" href="estilos.css">

<style>
/* Estilos para a sidebar horizontal */
.sidebar {
    width: 100%; /* Largura total da página */
    background-color: #f1f1f1; /* Cor de fundo da sidebar */
    padding: 20px; /* Espaçamento interno */
}

/* Estilos para os links na sidebar */
.sidebar ul {
    list-style-type: none; /* Remover marcadores da lista */
    padding: 0; /* Remover espaçamento interno da lista */
    display: flex; /* Tornar os links flexíveis (dispostos horizontalmente) */
}

.sidebar li {
    margin-right: 20px; /* Espaçamento entre os itens da lista */
}

.sidebar a {
    text-decoration: none; /* Remover sublinhado dos links */
    color: #333; /* Cor dos links */
}

/* Estilos para o conteúdo principal */
.content {
    padding: 20px; /* Espaçamento interno */
}

</style>


</head>
<body>
    <div class="sidebar">
        <!-- Conteúdo da sidebar aqui -->
        <h2>Sidebar</h2>
        <ul>
            <li><a href="#">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li><a href="#">Link 3</a></li>
        </ul>
    </div>

    <div class="content">
        <!-- Conteúdo principal aqui -->
        <h1>Conteúdo Principal</h1>
        <p>Texto do conteúdo principal...</p>
    </div>
</body>
</html>
