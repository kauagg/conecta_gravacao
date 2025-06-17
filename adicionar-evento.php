<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "conecta_festas");

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["erro" => "Erro na conexão com o banco."]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$nome = $data['nome'] ?? '';
$status = $data['status'] ?? '';

if (!$nome || !$status) {
  http_response_code(400);
  echo json_encode(["erro" => "Dados incompletos."]);
  exit;
}

$stmt = $conn->prepare("INSERT INTO eventos (nome, status) VALUES (?, ?)");
$stmt->bind_param("ss", $nome, $status);

if ($stmt->execute()) {
  echo json_encode(["sucesso" => true, "id" => $stmt->insert_id]);
} else {
  http_response_code(500);
  echo json_encode(["erro" => "Erro ao salvar evento."]);
}

$stmt->close();
$conn->close();
?>
