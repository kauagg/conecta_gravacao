<?php
session_start();
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "conecta_festas");
    if ($conn->connect_error) {
        $mensagem = "Erro de conexão com o banco.";
    } else {
        $email = $_POST['email'] ?? "";
        $senha = $_POST['senha'] ?? "";

        if (!$email || !$senha) {
            $mensagem = "Por favor, preencha todos os campos.";
        } else {
            $stmt = $conn->prepare("SELECT id, email, senha FROM clientes WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($cliente = $result->fetch_assoc()) {
                    if (password_verify($senha, $cliente['senha'])) {
                        $_SESSION['cliente_id'] = $cliente['id'];
                        $_SESSION['cliente_email'] = $cliente['email'];
                        header("Location: cliente-dashboard.php"); // Redireciona para dashboard
                        exit;
                    } else {
                        $mensagem = "Senha incorreta.";
                    }
                } else {
                    $mensagem = "Usuário não encontrado.";
                }
                $stmt->close();
            } else {
                $mensagem = "Erro na preparação da consulta.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login do Cliente - Conecta Festas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

    <div class="card p-4 shadow" style="width: 350px;">
        <h3 class="mb-4 text-center">Login do Cliente</h3>

        <?php if ($mensagem): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>

</body>
</html>
