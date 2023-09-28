<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloco de Notas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        textarea {
            width: 50%;
            height: 80vh;
            resize: none;
            border: 1px solid #ccc;
            padding: 10px;
        }
        button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <textarea id="texto" placeholder="Digite o seu texto aqui..."></textarea>
    <button id="salvar">Salvar</button>

    <script>
        const texto = document.getElementById('texto');
        const salvar = document.getElementById('salvar');

        salvar.addEventListener('click', () => {
            const conteudo = texto.value;
            const blob = new Blob([conteudo], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'SIASB.txt';
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    </script>
</body>
</html>
