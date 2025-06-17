<?php
session_start();

$dataFolder = __DIR__ . '/data';
$usuariosFile = $dataFolder . '/usuarios.json';

if (!file_exists($dataFolder)) {
    mkdir($dataFolder, 0777, true);
}

function lerUsuarios() {
    global $usuariosFile;
    if (!file_exists($usuariosFile)) {
        file_put_contents($usuariosFile, json_encode([]));
    }
    $json = file_get_contents($usuariosFile);
    return json_decode($json, true);
}

function salvarUsuarios($usuarios) {
    global $usuariosFile;
    file_put_contents($usuariosFile, json_encode($usuarios, JSON_PRETTY_PRINT));
}

// Se ainda não tem usuário admin, cria um padrão (usuario: admin, senha: 1234)
$usuarios = lerUsuarios();
if (empty($usuarios)) {
    $usuarios[] = [
        'usuario' => 'admin',
        'senha' => password_hash('1234', PASSWORD_DEFAULT),
        'nome_completo' => 'Administrador',
        'email' => 'admin@conectafestas.com'
    ];
    salvarUsuarios($usuarios);
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    foreach ($usuarios as $user) {
        if ($user['usuario'] === $usuario && password_verify($senha, $user['senha'])) {
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['nome'] = $user['nome_completo'];
            header('Location: eventos.php');
            exit;
        }
    }
    $erro = "Usuário ou senha inválidos.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Conecta Festas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>

  <!-- Tela de carregamento -->
  <div id="preloader">
    <img src="assets/images/truck.gif" alt="Carregando..." class="truck-animation">
  </div>

  <!-- Tela de Login -->
  <div class="login-box" id="loginBox">
    <img src="assets/images/Logo.png" alt="Conecta Festas" class="login-img">
    <form id="loginForm">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" placeholder="Digite seu email" required>
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" placeholder="Digite sua senha" required>
      </div>
      <a href="dashboard.html" class="btn btn-primary w-100">Entrar</a>
      <div id="mensagemErro" class="text-danger mt-3 text-center"></div>
    </form>
  </div>

  <script src="assets/js/loader.js"> </script>
</body>
</html>



<?php if ($erro): ?>
    <p style="color:red;"><?=htmlspecialchars($erro)?></p>
<?php endif; ?>