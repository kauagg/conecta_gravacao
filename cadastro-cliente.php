<?php
session_start();

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conexão com banco de dados
    $conn = new mysqli("localhost", "root", "", "conecta_festas");
    if ($conn->connect_error) {
        $erro = "Erro na conexão com o banco de dados.";
    } else {
        // Recebe e limpa dados do formulário
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmarSenha'] ?? '';

        // Validações básicas
        if (!$nome || !$email || !$telefone || !$senha || !$confirmarSenha) {
            $erro = "Preencha todos os campos.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = "Email inválido.";
        } elseif ($senha !== $confirmarSenha) {
            $erro = "As senhas não coincidem.";
        } elseif (strlen($senha) < 6) {
            $erro = "A senha deve ter ao menos 6 caracteres.";
        } else {
            // Verifica se email já existe
            $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $erro = "Email já cadastrado.";
            } else {
                $stmt->close();
                // Hash da senha
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                // Insere no banco
                $stmt = $conn->prepare("INSERT INTO clientes (nome, email, telefone, senha) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nome, $email, $telefone, $senha_hash);

                if ($stmt->execute()) {
                    $mensagem = "Cadastro realizado com sucesso! Você já pode <a href='cliente-login.php'>fazer login</a>.";
                    // Limpa os campos
                    $nome = $email = $telefone = "";
                } else {
                    $erro = "Erro ao cadastrar: " . $stmt->error;
                }
                $stmt->close();
            }
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
  <title>Cadastro de Cliente - Conecta Festas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="assets/css/cadastro-cliente.css">
</head>
<body>

  <div class="cadastro-box container mt-5" style="max-width: 450px;">
    <h2 class="mb-4">Cadastro de Cliente</h2>

    <?php if ($mensagem): ?>
      <div class="alert alert-success"><?= $mensagem ?></div>
    <?php endif; ?>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <form action="" method="POST" novalidate>
      <div class="mb-3">
        <label for="nome" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($nome ?? '') ?>">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">
      </div>
      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(99) 99999-9999" required value="<?= htmlspecialchars($telefone ?? '') ?>">
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required>
      </div>
      <div class="mb-3">
        <label for="confirmarSenha" class="form-label">Confirmar Senha</label>
        <input type="password" class="form-control" id="confirmarSenha" name="confirmarSenha" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
    </form>

    <p class="text-center mt-3">Já tem conta? <a href="cliente-login.php">Entrar</a></p>
  </div>

</body>
</html>
