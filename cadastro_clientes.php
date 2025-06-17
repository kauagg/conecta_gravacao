<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$dataFolder = __DIR__ . '/data';
$clientesFile = $dataFolder . '/clientes.json';

if (!file_exists($dataFolder)) {
    mkdir($dataFolder, 0777, true);
}

function lerClientes() {
    global $clientesFile;
    if (!file_exists($clientesFile)) {
        file_put_contents($clientesFile, json_encode([]));
    }
    $json = file_get_contents($clientesFile);
    return json_decode($json, true);
}

function salvarClientes($clientes) {
    global $clientesFile;
    file_put_contents($clientesFile, json_encode($clientes, JSON_PRETTY_PRINT));
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    if (!$nome || !$email || !$telefone) {
        $erro = "Preencha todos os campos.";
    } else {
        $clientes = lerClientes();
        foreach ($clientes as $c) {
            if ($c['email'] === $email) {
                $erro = "Email já cadastrado para outro cliente.";
                break;
            }
        }
        if (!$erro) {
            $clientes[] = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone
            ];
            salvarClientes($clientes);
            $sucesso = "Cliente cadastrado com sucesso!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Cliente - Conecta Festas</title>
</head>
<body>
    <h1>Cadastrar Novo Cliente</h1>

    <?php if ($erro): ?>
        <p style="color:red;"><?=htmlspecialchars($erro)?></p>
    <?php elseif ($sucesso): ?>
        <p style="color:green;"><?=htmlspecialchars($sucesso)?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="tel" name="telefone" placeholder="Telefone" required><br><br>
        <button type="submit">Cadastrar</button>
    </form>

    <p><a href="clientes.php">Voltar para Clientes</a></p>
</body>
</html>
