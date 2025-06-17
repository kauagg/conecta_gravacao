<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (!$email || $newPassword !== $confirmPassword) {
        $_SESSION['error'] = 'Dados inválidos ou senhas diferentes';
        header('Location: ../index.html');
        exit();
    }

    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    try {
        $stmt->execute([$hash, $email]);
        $_SESSION['success'] = 'Senha redefinida com sucesso';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao redefinir: ' . $e->getMessage();
    }

    header('Location: ../index.html');
    exit();
}
