<?php
session_start();
require_once '../../../config/config.php';

$conn = conectarDB();

if (isset($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

  $query = "SELECT id, username, role, status, apellidos, nombres FROM usuarios WHERE id = ?";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
    exit;
}
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    echo json_encode($usuario);
  } else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
  }

  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado.']);
}
