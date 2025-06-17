<?php
session_start();
require_once 'conexao.php';

$mensagem = '';
$mensagem_tipo = ''; // success ou error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'recoverEmail', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $mensagem = 'Email inválido.';
        $mensagem_tipo = 'error';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($stmt->rowCount()) {
            // Aqui você pode gerar token e enviar por e-mail (simulação)
            $mensagem = 'Link de recuperação enviado para o e-mail informado.';
            $mensagem_tipo = 'success';
        } else {
            $mensagem = 'Email não encontrado.';
            $mensagem_tipo = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Recuperar Senha - Conecta Festas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

  <div class="card p-4 shadow" style="width: 380px;">
    <h3 class="mb-4 text-center">Recuperar Senha</h3>

    <?php if ($mensagem): ?>
      <div class="alert alert-<?= $mensagem_tipo === 'success' ? 'success' : 'danger' ?>" role="alert">
        <?= htmlspecialchars($mensagem) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="recoverEmail" class="form-label">Digite seu e-mail:</label>
        <input
          type="email"
          class="form-control"
          id="recoverEmail"
          name="recoverEmail"
          required
          value="<?= htmlspecialchars($_POST['recoverEmail'] ?? '') ?>"
          placeholder="exemplo@email.com"
        />
      </div>

      <button type="submit" class="btn btn-primary w-100">Enviar Link de Recuperação</button>
    </form>

    <div class="mt-3 text-center">
      <a href="../index.html">Voltar para Login</a>
    </div>
  </div>

</body>
</html>
