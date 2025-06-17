<?php
session_start();

$erro = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conexão com banco de dados
    $conn = new mysqli("localhost", "root", "", "conecta_festas");
    if ($conn->connect_error) {
        $erro = "Erro na conexão com o banco de dados.";
    } else {
        // Recebe e limpa dados do formulário
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (!$email || !$senha) {
            $erro = "Preencha todos os campos.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = "Email inválido.";
        } else {
            // Busca cliente pelo email
            $stmt = $conn->prepare("SELECT id, nome, senha FROM clientes WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verifica a senha hash
                if (password_verify($senha, $user['senha'])) {
                    // Login OK: salva dados na sessão
                    $_SESSION['cliente_id'] = $user['id'];
                    $_SESSION['cliente_nome'] = $user['nome'];
                    header("Location: dashboard_cliente.php"); // página após login
                    exit;
                } else {
                    $erro = "Senha incorreta.";
                }
            } else {
                $erro = "Email não cadastrado.";
            }
            $stmt->close();
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login do Cliente - Conecta Festas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/cliente-login.css">
</head>
<body>

  <div class="login-container container mt-5" style="max-width: 400px;">
    <img src="assets/images/2Logo.png" alt="Logo Conecta" class="logo mb-3" style="width: 100%; max-width: 200px;">
    <h2 class="form-title mb-4 text-center">Login do Cliente</h2>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form id="clienteForm" method="POST" action="">
      <div class="mb-3 d-none" id="campoNome">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required />
      </div>
      <button type="submit" class="btn btn-primary w-100" id="btnSubmit">Entrar</button>
    </form>

    <div class="mt-3 text-center">
      <div class="toggle" style="cursor:pointer; color: #0d6efd;" onclick="location.href='cadastro_cliente.php'">Ainda não tem conta? Cadastre-se</div>
      <div class="toggle mt-2" style="cursor:pointer; color: #0d6efd;" data-bs-toggle="modal" data-bs-target="#recuperarSenhaModal">Esqueceu a senha?</div>
    </div>
  </div>

  <!-- Modal de Recuperar Senha -->
  <div class="modal fade" id="recuperarSenhaModal" tabindex="-1" aria-labelledby="recuperarSenhaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formRecuperarSenha" method="POST" action="recuperar_senha.php">
          <div class="modal-header">
            <h5 class="modal-title" id="recuperarSenhaLabel">Recuperar Senha</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <label for="emailRecuperar" class="form-label">Digite seu e-mail:</label>
            <input type="email" class="form-control" id="emailRecuperar" name="emailRecuperar" required>
            <div id="mensagemRecuperar" class="mt-2 text-danger"></div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/cliente-login.js"></script>

</body>
</html>
