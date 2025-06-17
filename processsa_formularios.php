<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $evento = filter_input(INPUT_POST, 'evento', FILTER_SANITIZE_STRING);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare("INSERT INTO solicitacoes (nome, email, telefone, evento, mensagem) VALUES (?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$nome, $email, $telefone, $evento, $mensagem]);
        $_SESSION['success'] = 'Solicitação enviada com sucesso!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao enviar: ' . $e->getMessage();
    }

    header('Location: ../pages/formulario.php');
    exit();
}
