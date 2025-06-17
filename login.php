<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :username AND tipo = :tipo LIMIT 1");
    $stmt->execute(['username' => $username, 'tipo' => $userType]);

    if ($stmt->rowCount()) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['tipo'] = $user['tipo'];

            if ($userType === 'cliente') {
                header('Location: ../pages/dashboard_cliente.php');
            } else {
                header('Location: ../pages/dashboard_empresa.php');
            }
            exit();
        } else {
            $_SESSION['error'] = 'Senha incorreta';
        }
    } else {
        $_SESSION['error'] = 'Usuário não encontrado';
    }

    header('Location: ../index.html');
    exit();
}
