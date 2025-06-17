<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'newUsername', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'newEmail', FILTER_VALIDATE_EMAIL);
    $password = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'As senhas não coincidem';
        header('Location: ../index.html');
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (cpf, username, email, senha, nome, telefone, endereco, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, 'cliente')");

    try {
        $stmt->execute([$cpf, $username, $email, $hash, $nome, $telefone, $endereco]);
        $_SESSION['success'] = 'Cadastro realizado com sucesso';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao cadastrar: ' . $e->getMessage();
    }

    header('Location: ../index.html');
    exit();
}
