<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

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

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $nome = trim($_POST['nome_completo'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$usuario || !$senha || !$nome || !$email) {
        $erro = "Preencha todos os campos.";
    } else {
        $usuarios = lerUsuarios();
        foreach ($usuarios as $u) {
            if ($u['usuario'] === $usuario) {
                $erro = "Usuário já existe.";
                break;
            }
            if ($u['email'] === $email) {
                $erro = "Email já cadastrado.";
                break;
            }
        }
        if (!$erro) {
            $usuarios[] = [
                'usuario' => $usuario,
                'senha' => password_hash($senha, PASSWORD_DEFAULT),
                'nome_completo' => $nome,
                'email' => $email
            ];
            salvarUsuarios($usuarios);
            $sucesso = "Usuário cadastrado com sucesso!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Usuário - Conecta Festas</title>
</head>
<body>
    <h1>Cadastrar Novo Usuário</h1>

    <?php if ($erro): ?>
        <p style="color:red;"><?=htmlspecialchars($erro)?></p>
    <?php elseif ($sucesso): ?>
        <p style="color:green;"><?=htmlspecialchars($sucesso)?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuário" required><br><br>
        <input type="password" name="senha" placeholder="Senha" required><br><br>
        <input type="text" name="nome_completo" placeholder="Nome Completo" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <button type="submit">Cadastrar</button>
    </form>

    <p><a href="eventos.php">Voltar para Eventos</a></p>
</body>
</html>
