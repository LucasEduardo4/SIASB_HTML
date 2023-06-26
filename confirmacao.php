<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Dados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Confirmação de Dados</h1>
    <div class="info">
        <strong>Nome:</strong> <?php echo $nome; ?>
    </div>
    <div class="info">
        <strong>CPF:</strong> <?php echo $cpf; ?>
    </div>
    <div class="info">
        <strong>Matrícula:</strong> <?php echo $matricula; ?>
    </div>
    <div class="info">
        <strong>Setor:</strong> <?php echo $setor; ?>
    </div>
    <div class="info">
        <strong>Email:</strong> <?php echo $email; ?>
    </div>
</body>
</html>
