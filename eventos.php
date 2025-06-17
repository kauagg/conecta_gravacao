<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$dataFolder = __DIR__ . '/data';
$eventosFile = $dataFolder . '/eventos.json';

if (!file_exists($dataFolder)) {
    mkdir($dataFolder, 0777, true);
}

function lerEventos() {
    global $eventosFile;
    if (!file_exists($eventosFile)) {
        file_put_contents($eventosFile, json_encode([]));
    }
    $json = file_get_contents($eventosFile);
    return json_decode($json, true);
}

function salvarEventos($eventos) {
    global $eventosFile;
    file_put_contents($eventosFile, json_encode($eventos, JSON_PRETTY_PRINT));
}

$eventos = lerEventos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $data = $_POST['data'] ?? '';
    $status = $_POST['status'] ?? '';

    if ($nome && $data && $status) {
        $novoEvento = [
            'id' => time(),
            'nome' => $nome,
            'data_evento' => $data,
            'status' => $status,
            'criado_por' => $_SESSION['usuario'],
            'criado_em' => date('Y-m-d H:i:s')
        ];
        $eventos[] = $novoEvento;
        salvarEventos($eventos);
        header('Location: eventos.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Eventos - Conecta Festas</title>
  <link rel="stylesheet" href="assets/css/eventos.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="sidebar">
    <h4>Conecta Festas</h4>
    <hr>
    <a href="dashboard.html"><i class="fas fa-chart-line"></i> Visão Geral</a>
    <a href="eventos.html"><i class="fas fa-calendar-check"></i> Eventos Agendados</a>
    <a href="clientes.html"><i class="fas fa-users"></i> Clientes</a>
    <a href="formulario.html"><i class="fas fa-user"></i> Fomulário</a>
    <a href="configuracoes.html"><i class="fas fa-cogs"></i> Configurações</a>
    <a href="sair.html"><i class="fas fa-sign-out-alt"></i> Sair</a>
  </div>
  <div class="content">
    <h2>Meus Eventos</h2>
    <form id="formEvento" class="mb-4">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" class="form-control" id="nomeEvento" placeholder="Nome do Evento" required>
        </div>
        <div class="col-md-3">
          <select class="form-select" id="statusEvento" required>
            <option value="">Status</option>
            <option value="success">Confirmado</option>
            <option value="warning">Pendente</option>
          </select>
        </div>
        <div class="col-md-3">
          <button class="btn btn-primary w-100" type="submit">Adicionar Evento</button>
        </div>
      </div>
    </form>

    <ul class="list-group" id="listaEventos"></ul>
  </div>
 <script src="assets/js/eventos.js"></script>
    <?php foreach ($eventos as $evento): ?>
        <li>
            <?=htmlspecialchars($evento['nome'])?> - <?=htmlspecialchars($evento['data_evento'])?> - <strong><?=htmlspecialchars($evento['status'])?></strong>
        </li>
    <?php endforeach; ?>
    </ul>

    <a href="clientes.php">Clientes</a> | <a href="configuracoes.php">Configurações</a> | <a href="logout.php">Sair</a> | <a href="cadastro_usuarios.php">Cadastrar Usuário</a>
</body>
</html>
