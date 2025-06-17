<?php
// Conexão com banco de dados (ajuste as credenciais)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conecta_festas";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para executar query com tratamento de exceção
function executarQuery($conn, $sql, $colunaRetorno) {
    try {
        $resultado = $conn->query($sql);
        $row = $resultado->fetch_assoc();
        return $row[$colunaRetorno] ?? 0;
    } catch (mysqli_sql_exception $e) {
        // Retorna 0 caso tabela não exista ou erro na query
        return 0;
    }
}

// Consultas
$sqlGanhos = "SELECT SUM(valor_pago) AS total_ganhos FROM pagamentos";
$sqlClientes = "SELECT COUNT(*) AS total_clientes FROM clientes";
$sqlPedidos = "SELECT COUNT(*) AS total_pedidos FROM eventos";

$ganhos = executarQuery($conn, $sqlGanhos, 'total_ganhos');
$clientes = executarQuery($conn, $sqlClientes, 'total_clientes');
$pedidos = executarQuery($conn, $sqlPedidos, 'total_pedidos');

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - Conecta Festas</title>
  <link rel="stylesheet" href="assets/css/dashboard.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="sidebar">
    <h4>Conecta Festas</h4>
    <hr />
    <a href="dashboard-empresa.php"><i class="fas fa-chart-line"></i> Visão Geral</a>
    <a href="eventos-empresa.php"><i class="fas fa-calendar-check"></i> Eventos Agendados</a>
    <a href="clientes-empresa.php"><i class="fas fa-users"></i> Clientes</a>
    <a href="formulario-empresa.php"><i class="fas fa-user"></i> Formulário</a>
    <a href="configuracoes-empresa.php"><i class="fas fa-cogs"></i> Configurações</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
  </div>

  <div class="content">
    <h2>Visão Geral</h2>
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card text-white bg-primary p-3">
          <div>Total de Ganhos</div>
          <div class="counter" id="ganhos">R$ <?= number_format($ganhos, 2, ',', '.') ?></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-success p-3">
          <div>Clientes</div>
          <div class="counter" id="clientes"><?= $clientes ?></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-info p-3">
          <div>Pedidos</div>
          <div class="counter" id="pedidos"><?= $pedidos ?></div>
        </div>
      </div>
      <div class="col-md-3 d-flex align-items-center">
        <button class="btn btn-outline-secondary w-100" onclick="baixarRelatorio()">Baixar Relatório</button>
      </div>
    </div>
    <canvas id="graficoGanhos" height="100"></canvas>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="assets/js/dashboard.js"></script>
  <script>
    const ctx = document.getElementById('graficoGanhos');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
        datasets: [{
          label: 'Ganhos Mensais',
          data: [5000, 7000, 4000, 9000, 6000, 8000],
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    function baixarRelatorio() {
      alert("Função de download de relatório em desenvolvimento.");
    }
  </script>
</body>
</html>
