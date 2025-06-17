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

$clientes = lerClientes();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Clientes - Conecta Festas</title>
</head>
<body>
    <h1>Clientes</h1>
    <ul>
    <?php foreach ($clientes as $cliente): ?>
        <li><?=htmlspecialchars($cliente['nome'])?> - <?=htmlspecialchars($cliente['email'] ?? '')?></li>
    <?php endforeach; ?>
    </ul>

    <a href="eventos.php">Eventos</a> | <a href="configuracoes.php">Configurações</a> | <a href="logout.php">Sair</a> | <a href="cadastro_clientes.php">Cadastrar Cliente</a>
</body>
</html>
