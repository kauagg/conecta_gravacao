<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "conecta_festas");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro de conexão com o banco."]);
    exit;
}

// Recebe dados JSON
$data = json_decode(file_get_contents("php://input"), true);

// Valida campos
if (
    !isset($data['email'], $data['senha']) ||
    !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
    strlen($data['senha']) < 6
) {
    http_response_code(400);
    echo json_encode(["erro" => "Dados inválidos ou incompletos."]);
    exit;
}

$email = $data['email'];
$senha = $data['senha'];

// Verifica se email já existe
$stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    http_response_code(409); // conflito (email já cadastrado)
    echo json_encode(["erro" => "Email já cadastrado."]);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Criptografa senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Insere novo cliente
$stmt = $conn->prepare("INSERT INTO clientes (email, senha) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $senha_hash);

if ($stmt->execute()) {
    echo json_encode(["sucesso" => true]);
} else {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao salvar no banco: " . $conn->error]);
}

$stmt->close();
$conn->close();
