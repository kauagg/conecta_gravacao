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

if (!$id) {
  http_response_code(400);
  echo json_encode(["erro" => "ID ausente."]);
  exit;
}

$stmt = $conn->prepare("DELETE FROM eventos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  echo json_encode(["sucesso" => true]);
} else {
  http_response_code(500);
  echo json_encode(["erro" => "Erro ao excluir evento."]);
}

$stmt->close();
$conn->close();
?>
