<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<style>

body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
}

header {
  background-color: #333;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px;
}

header h1 {
  margin: 0;
}

#user-icon {
  display: flex;
  align-items: center;
  cursor: pointer;
}

#user-icon img {
  width: 30px;
  height: 30px;
  margin-right: 5px;
}

#user-dropdown {
  position: absolute;
  background-color: #f9f9f9;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  padding: 12px;
  right: 10px;
  top: 60px;
}

#user-dropdown ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

#user-dropdown ul li {
  margin-bottom: 5px;
}

#user-dropdown ul li a {
  color: #333;
  text-decoration: none;
}

.hidden {
  display: none;
}


</style>

<body>

<header>
    <div id="user-icon" class="hidden">
      <img src="user-icon.png" alt="Ícone de Usuário">
      <div id="user-dropdown" class="hidden">
        <ul>
          <li><a href="profile.html">Perfil</a></li>
          <li><a href="#" onclick="logout()">Logout</a></li>
        </ul>
      </div>
    </div>
  </header>
  
  <main>
    <h2>Login</h2>
    <form id="login-form">
      <label for="username">Usuário:</label>
      <input type="text" id="username" required>
      
      <label for="password">Senha:</label>
      <input type="password" id="password" required>
      
      <button type="submit">Entrar</button>
    </form>
  </main>

  <script src="script.js"></script>

<script> // Função para alternar a visibilidade do dropdown
const loginForm = document.getElementById('login-form');
const userIcon = document.getElementById('user-icon');
const userDropdown = document.getElementById('user-dropdown');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');

loginForm.addEventListener('submit', function(e) {
  e.preventDefault();

  // Aqui você faria a lógica de autenticação, verificando o usuário e senha com o servidor, por exemplo.
  // Por enquanto, vamos considerar que o login foi bem-sucedido se ambos os campos estiverem preenchidos.
  const username = usernameInput.value;
  const password = passwordInput.value;
  if (username && password) {
    loginSuccess();
  }
});

function loginSuccess() {
  // Exibe o ícone do usuário na barra de navegação
  userIcon.classList.remove('hidden');

  // Esconde o formulário de login
  loginForm.classList.add('hidden');
}

function logout() {
  // Aqui você faria a lógica de logout, como fazer uma requisição para o servidor encerrar a sessão.
  // Por enquanto, vamos apenas simular o logout escondendo o ícone do usuário e exibindo o formulário de login novamente.
  userIcon.classList.add('hidden');
  loginForm.classList.remove('hidden');
}

userIcon.addEventListener('click', function() {
  // Exibe ou esconde o dropdown do usuário ao clicar no ícone
  userDropdown.classList.toggle('hidden');
});

// Fecha o dropdown do usuário quando clicar fora dele
document.addEventListener('click', function(event) {
  if (!userIcon.contains(event.target)) {
    userDropdown.classList.add('hidden');
  }
});

</script>

</body>
</html>


