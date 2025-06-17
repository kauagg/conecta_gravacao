<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$dataFolder = __DIR__ . '/data';
$configFile = $dataFolder . '/configuracoes.json';

if (!file_exists($dataFolder)) {
    mkdir($dataFolder, 0777, true);
}

function lerConfig() {
    global $configFile;
    if (!file_exists($configFile)) {
        file_put_contents($configFile, json_encode([]));
    }
    $json = file_get_contents($configFile);
    return json_decode($json, true);
}

function salvarConfig($config) {
    global $configFile;
    file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
}

$config = lerConfig();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cor = $_POST['cor'] ?? 'white';
    $config['cor_fundo'] = $cor;
    salvarConfig($config);
    header('Location: configuracoes.php');
    exit;
}

$cor_fundo = $config['cor_fundo'] ?? 'white';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Configurações - Conecta Festas</title>
</head>
<body style="background-color: <?=htmlspecialchars($cor_fundo)?>">
    <h1>Configurações</h1>
    <form method="POST">
        <label>Cor de Fundo:</label>
        <input type="color" name="cor" value="<?=htmlspecialchars($cor_fundo)?>" />
        <button type="submit">Salvar</button>
    </form>
    <a href="eventos.php">Eventos</a> | <a href="clientes.php">Clientes</a> | <a href="logout.php">Sair</a>
</body>
</html>
