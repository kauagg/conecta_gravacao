<?php
$host = 'localhost';
$dbname = 'conectafestas';
$username = 'root'; // Atualize com seu usuário MySQL
$password = 'senac'; // Atualize com sua senha MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Falha na conexão: " . $e->getMessage());
}
?>