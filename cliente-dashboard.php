<?php
session_start();

// Verifica se cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: cliente-login.php');
    exit;
}

// Dados do cliente da sessão
$clienteNome = $_SESSION['cliente_nome'] ?? '';
$clienteEmail = $_SESSION['cliente_email'] ?? ''; // opcional, caso tenha armazenado email na sessão

// Se não tiver email na sessão, buscar no banco (opcional)
if (!$clienteEmail) {
    // Conexão com banco
    $conn = new mysqli("localhost", "root", "", "conecta_festas");
    if ($conn->connect_error) {
        die("Erro na conexão com o banco.");
    }
    $stmt = $conn->prepare("SELECT email FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['cliente_id']);
    $stmt->execute();
    $stmt->bind_result($emailDb);
    $stmt->fetch();
    $clienteEmail = $emailDb;
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard do Cliente - Conecta Festas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/cliente-dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="flex flex-col">
    <style>
 body {
            font-family: 'Inter', sans-serif;
            background-color: #111827;
            color: #e5e7eb;
            margin: 0;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            background-color: #1f2937;
            width: 260px;
            height: 100vh;
            position: fixed;
            transition: width 0.3s ease;
            overflow-y: auto;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar .logo {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid #374151;
        }
        .sidebar .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            transition: opacity 0.3s ease;
        }
        .sidebar.collapsed .logo-text {
            opacity: 0;
        }
        .sidebar nav a {
            color: #d1d5db;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            transition: background 0.2s ease;
        }
        .sidebar nav a:hover, .sidebar nav a.active {
            background-color: #374151;
            color: #ffffff;
        }
        .sidebar.collapsed nav a span {
            display: none;
        }
        .content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            width: calc(100% - 260px); /* Ensure content takes remaining width */
            box-sizing: border-box;
        }
        .content.collapsed {
            margin-left: 80px;
            width: calc(100% - 80px); /* Adjust width when sidebar is collapsed */
        }
        .dashboard-card {
            background-color: #1f2937;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
            width: 100%; /* Ensure it takes full available width */
            max-width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }
        .chart-container {
            width: 100%; /* Ensure it takes full width of parent */
            max-width: 100%;
            height: 400px; /* Fixed height for the chart */
            margin: 2rem auto;
            position: relative;
        }
        .toggle-btn {
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .toggle-btn:hover {
            transform: rotate(90deg);
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            .sidebar .logo-text {
                opacity: 0;
            }
            .sidebar nav a span {
                display: none;
            }
            .content {
                margin-left: 80px;
                width: calc(100% - 80px);
            }
            .chart-container {
                height: 300px; /* Adjusted height for smaller screens */
            }
        }
    

    </style>
    <!-- Menu Lateral -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="assets/images/2Logo.png" alt="Logo">
        </div>
        <nav class="mt-4">
            <a href="cliente-dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            <a href="cliente-orcamentos.php"><i class="fas fa-file-invoice-dollar"></i><span>Meus Orçamentos</span></a>
            <a href="cliente-eventos.php"><i class="fas fa-calendar-alt"></i><span>Meus Eventos</span></a>
            <a href="cliente-mensagens.php" class="active"><i class="fas fa-envelope"></i><span>Mensagens</span></a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Sair</span></a>
        </nav>
    </div>

    <!-- Área de Conteúdo -->
    <div class="content" id="content">
        <div class="dashboard-card p-4">
            <h2 class="text-3xl font-semibold mb-6">Bem-vindo, <span id="clienteEmail"><?= htmlspecialchars($clienteEmail) ?></span>!</h2>
            <p class="text-gray-400 mb-6">Visão geral dos seus eventos, orçamentos e gastos (Atualizado em 18/05/2025 14:23).</p>

            <!-- Visão Geral -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-800 p-4 rounded-lg text-white">
                    <h3 class="text-lg font-medium">Eventos Ativos</h3>
                    <p class="text-2xl mt-2">5</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg text-white">
                    <h3 class="text-lg font-medium">Orçamentos Pendentes</h3>
                    <p class="text-2xl mt-2">3</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg text-white">
                    <h3 class="text-lg font-medium">Gasto Total</h3>
                    <p class="text-2xl mt-2">R$ 12.500,00</p>
                </div>
            </div>

            <!-- Gráfico -->
            <div class="chart-container">
                <h3 class="text-lg font-medium mb-4 text-center text-white">Gastos Totais</h3>
                <canvas id="gastosChart"></canvas>
            </div>
        </div>
    </div>

    <script src="assets/js/cliente-dashboard.js"></script>
    <script>
        // Exemplo básico de gráfico (pode ajustar com dados reais via AJAX ou PHP)
        const ctx = document.getElementById('gastosChart').getContext('2d');
        const gastosChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Gastos (R$)',
                    data: [2000, 1500, 1800, 1200, 2200, 2500],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Função para logout (redirecionar para script logout.php)
        function sair() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
