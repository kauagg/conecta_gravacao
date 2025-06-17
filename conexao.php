<?php
// Configurações do Banco de Dados
$host = 'localhost';
$dbname = 'conecta_festas';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

// DSN - Data Source Name
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Opções do PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Exibir erros como exceções
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retornar dados como array associativo
    PDO::ATTR_EMULATE_PREPARES => false, // Usar prepared statements reais
];

// Criar conexão
try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // echo "Conexão bem-sucedida!"; // (Descomenta para testar)
} catch (PDOException $e) {
    die('Erro na conexão com o banco de dados: ' . $e->getMessage());
}
?>
