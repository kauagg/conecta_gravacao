<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "conecta_festas");

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["erro" => "Erro na conexão com o banco."]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$novoStatus = $data['status'] ?? null;

if (!$id || !$novoStatus) {
  http_response_code(400);
  echo json_encode(["erro" => "ID ou status ausente."]);
  exit;
}

$stmt = $conn->prepare("UPDATE eventos SET status = ? WHERE id = ?");
$stmt->bind_param("si", $novoStatus, $id);

if ($stmt->execute()) {
  echo json_encode(["sucesso" => true]);
} else {
  http_response_code(500);
  echo json_encode(["erro" => "Erro ao atualizar status."]);
}

$stmt->close();
$conn->close();
?>
