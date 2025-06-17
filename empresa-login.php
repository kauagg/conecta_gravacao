<?php
session_start();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Validação simples de login
    if ($email === 'teste@conectafestas.com' && $senha === '123456') {
        // Login ok, redireciona para dashboard
        // Poderia salvar info de sessão aqui, por exemplo:
        $_SESSION['usuario'] = $email;
        header('Location: dashboard-empresa.php');
        exit;
    } else {
        $erro = 'Email ou senha inválidos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Conecta Festas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/index.css" />
  <style>
    /* Mantém efeito de transição do login */
    #loginBox {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.5s ease, transform 0.5s ease;
    }
    #loginBox.show {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>

  <!-- Tela de carregamento -->
  <div id="preloader">
    <img src="assets/images/truck.gif" alt="Carregando..." class="truck-animation" />
  </div>

  <!-- Caixa de Login -->
  <div class="login-box" id="loginBox">
    <img src="assets/images/2Logo.png" alt="Conecta Festas" class="login-img" />
    <h2>Login da Empresa</h2>
    <form method="POST" id="loginForm" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          placeholder="Digite seu email"
          required
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
        />
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input
          type="password"
          class="form-control"
          id="senha"
          name="senha"
          placeholder="Digite sua senha"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
      <?php if ($erro): ?>
        <div id="mensagemErro" class="text-danger mt-3"><?= $erro ?></div>
      <?php endif; ?>
    </form>
  </div>

  <script>
    // Simula carregamento antes de mostrar login
    window.onload = function () {
      setTimeout(() => {
        document.getElementById('preloader').style.display = 'none';
        const loginBox = document.getElementById('loginBox');
        loginBox.classList.add('show');
      }, 2000);
    };
  </script>
</body>
</html>
