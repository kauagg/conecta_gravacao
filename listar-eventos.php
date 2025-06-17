<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "conecta_festas");

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["erro" => "Erro na conexão com o banco."]);
  exit;
}

$resultado = $conn->query("SELECT id, nome, status FROM eventos ORDER BY data_criacao DESC");
$eventos = [];

while ($row = $resultado->fetch_assoc()) {
  $eventos[] = $row;
}

echo json_encode($eventos);

$conn->close();
?>
