<!DOCTYPE html>
<html>
<head>
<style>
    /* Estilos iniciais para o iframe */
    #meuIframe {
        width: 500px;
        height: 300px;
        background-color: lightblue; /* Tema claro */
    }
</style>
<script>
    function mudarTema(tema) {
        var iframe = document.getElementById("meuIframe").contentWindow.document;

        if (tema === "claro") {
            iframe.body.style.backgroundColor = "lightblue";
            iframe.body.style.color = "black";
        } else if (tema === "escuro") {
            iframe.body.style.backgroundColor = "darkgray";
            iframe.body.style.color = "white";
        }
    }
</script>
</head>
<body>

<button onclick="mudarTema('claro')">Tema Claro</button>
<button onclick="mudarTema('escuro')">Tema Escuro</button>

<iframe id="meuIframe" src="https://www.exemplo.com"></iframe>

</body>
</html>
