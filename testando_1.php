<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../icons/warning.png" sizes="192x192" />
    <link rel="stylesheet" href="../../sidebars/bootstrap.min.css">
    <link rel="stylesheet" href="../lib/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="./../../flowSite/verificaSessao.js"></script>

    <style>

/* .table-heading
{
    --bs-table-striped-bg:#00a383;

} */
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


        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            padding-top: 100px;

        }

        /* Header Styles */
        .header {
            background-color: #ffffff;
            color: #fff;
            padding: 10px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: space-between;
            
        }

        .back-button {
            color: #000000;
            text-decoration: none;
            margin-right: 10px;
            float: left;
            margin-left: 30px;
        }

        .title {
            /* margin: auto; */
            font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            padding-top: 30px; 
            padding-bottom: 30px;
            padding-right: 950px;
            font-size: 26px;
            color: #000000;
            
        }

        /* Main Content Styles */
        .main-content {
            max-width: 1000px;
            margin: auto;
            padding: 50px;
            padding-left: 100px;
            padding-right: 100px;
            background-color: #b6b5b51a; 
            border-radius: 15px;
            box-shadow:  10px 11px rgba(185, 185, 185, 0.815);

            text-align: center; /* Center text in the main content */
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .add-status-form {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .add-status-form label {
            margin-right: 10px;
        }

        .add-status-form input[type="text"] {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add-status-form button {
            background-color: #00a383;
            color: #fff;
            border: none;
            padding: 8px 15px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-status-form button i {
            margin-right: 5px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            border-radius: 10px;
            overflow: hidden;
            /* background-color: #fff; */
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
            
        }

        .table-heading {
            background: linear-gradient(135deg, #007bff, #00cc99);
            color: #fff;
        }

        .table-heading th {
            padding: 4px;
        }

        table th,
        table td {
            padding: 12px;
            /* border: 1px solid #00cc99; */
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Button Styles */
        button {
            background-color: #00cc99;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button i {
            margin-right: 5px;
        }

        button:hover {
            background-color: #007bff;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .main-content {
                padding: 10px;
            }

            .add-status-form {
                flex-direction: column;
                
            }

            .add-status-form input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }
        }

    </style>

    <title>CONFIGURAÇÕES DE STATUS</title>
</head>

<body onload="init()">
    <header class="header">
        <a href="../index.html" class="back-button"><i style="font-size: 40px" class="bi-arrow-left-circle bi-lg"></i></a>
        <h1 class="title">CONFIGURAÇÕES DE STATUS</h1>
    </header>
    
<main class="main-content">
        <form class="add-status-form">
            <label style="font-size: 13px;font-weight: bold;" for="novoStatus">ADICIONAR STATUS:</label>
            <input type="text" name="novoStatus" id="novoStatus" required>
            <button onclick="submitForm()"><i class="bi bi-plus"></i>Adicionar</button>
        </form>
        <div id="tableStatusContainer">
            <!-- <h2>Status Existentes:</h2> -->
            <table border id="tableStatus" class="table table-hover">
                <tr class="table-heading">
                    <th>ID Status</th>
                    <th>Descrição status</th>
                    <th>Ações</th>
                </tr>
            </table>
        </div>
        <div id="resultado"></div>

        <header class="header">
    <a href="../index.html" class="back-button"><i style="font-size: 40px" class="bi-arrow-left-circle bi-lg"></i></a>
    <h1 class="title">CONFIGURAÇÕES DE STATUS</h1>
    <button id="theme-toggle">Alterar Tema</button>
</header>

    </main>

    <script src="script.js"></script> <!-- External JavaScript file -->
</body>
</html>

    
    <script>
        function init() {
            loadStatus()
                .then(hideSaveButton)
                .then(setupEditButtons)
                .catch(function(error) {
                    console.error(error);
                });
        }

        function loadStatus() {
            return new Promise(function(resolve, reject) {
                var tableStatus = document.getElementById("tableStatus");

                var xhr = new XMLHttpRequest();
                xhr.open("GET", "configStatus.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            tableStatus.innerHTML += xhr.responseText;
                            resolve();
                        } else {
                            tableStatus.innerHTML += "Falha ao carregar os status";
                        }
                    }
                };
                xhr.send();
            });
        }

        function hideSaveButton() {
            var saveButtons = document.getElementsByClassName("bi bi-check-square");
            for (var i = 0; i < saveButtons.length; i++) {
                var saveButton = saveButtons[i];
                saveButton.style.display = "none";
            }
        }

        function submitForm() {
            var novoStatus = document.getElementsByName('novoStatus')[0].value;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "configStatus.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    setupEditButtons();
                    window.location.reload();
                }
            };
            
            xhr.send("novoStatus=" + encodeURIComponent(novoStatus));
        }



        function setupEditButtons() {
            var editButtons = document.getElementsByClassName("bi bi-pencil");
            var saveButtons = document.getElementsByClassName("bi bi-check-square");
            var removeButtons = document.getElementsByClassName("bi bi-trash");

            for (var i = 0; i < editButtons.length; i++) {
                (function(id) {
                    var editButton = editButtons[i];
                    editButton.addEventListener("click", function() {
                        var element = document.querySelector("#tableStatus tr:nth-child(" + (id + 1) + ")");
                        var ref = element.cells[0].innerText;
                        openEditInput(ref);
                    });

                    var saveButton = saveButtons[i];
                    saveButton.addEventListener("click", function() {
                        var element = document.querySelector("#tableStatus tr:nth-child(" + (id + 1) + ")");
                        var ref = element.cells[0].innerText;
                        saveEditedValue(ref);
                    });

                    var removeButton = removeButtons[i];
                    removeButton.addEventListener("click", function() {
                    // Pergunta ao usuário se deseja realmente excluir a anotação
                    var confirmExclusao = confirm("Tem certeza que deseja excluir este status?"); //essa função abre um alerta com a mensagem selecionada

                    if (confirmExclusao) {
                    var element = document.querySelector("#tableStatus tr:nth-child(" + (id + 1) + ")");
                    var ref = element.cells[0].innerText;
                    removeValue(ref);
    }
});

                })(i);
            }
        }

        function openEditInput(id) {
            if(id == "ID Status"){
                id = 1;
            }
            var cell = document.getElementById("desc-" + id);

            if (cell) {
                var descValue = cell.innerHTML;
                var inputElement = document.createElement("input");
                inputElement.type = "text";
                inputElement.value = descValue;
                inputElement.id = "edit-input-" + id;
                cell.innerHTML = "";
                cell.appendChild(inputElement);
                document.getElementById("edit-btn-" + id).style.display = "none";
                document.getElementById("trash-btn-" + id).style.display = "none";
                document.getElementById("save-btn-" + id).style.display = "inline-block";
            }
        }

        function saveEditedValue(id) {
            if(id == "ID Status"){
                id = 1;
            }
            var editedInput = document.getElementById("edit-input-" + id);
            var editedValue = editedInput.value;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "configStatus.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var cell = document.getElementById("desc-" + id);
                        cell.innerHTML = editedValue;
                        document.getElementById("edit-btn-" + id).style.display = "inline-block";
                        document.getElementById("trash-btn-" + id).style.display = "inline-block";
                        document.getElementById("save-btn-" + id).style.display = "none";
                    } else {
                        alert("Erro ao atualizar o status: " + response.message);
                    }
                }
            };
            xhr.send("editedValue=" + encodeURIComponent(editedValue) + "&id=" + id);
        }

        function removeValue(id) {
            if(id == "ID Status"){
                id = 1;
            }
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "configStatus.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.reload();
                }
            };
            xhr.send("deletedId=" + id);
        }

        document.addEventListener("DOMContentLoaded", function() {
    const themeToggle = document.getElementById("theme-toggle");
    const body = document.body;

    themeToggle.addEventListener("click", function() {
        body.classList.toggle("dark-theme");
    });
});

    </script>
</body>
</html>