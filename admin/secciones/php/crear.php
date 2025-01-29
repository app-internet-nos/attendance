<?php
session_start();
require_once 'utils.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', '0');

try {
  verificarMetodoPost();

  $conn = conectarDB();

  $inputData = file_get_contents('php://input');
  $data = json_decode($inputData, true);
  if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Error al decodificar JSON de entrada: ' . json_last_error_msg());
  }

  $data = validarEntrada($data);

  if (empty($data['id_programa_estudio']) || empty($data['id_ciclo']) || empty($data['año'])) {
    throw new Exception('Todos los campos son obligatorios.');
  }

  // Consulta para verificar si la seccion a existe
  $stmt = $conn->prepare("SELECT * FROM secciones WHERE id_programa_estudio = ? AND id_ciclo = ? AND año = ?");
  $stmt->bind_param('iis', $data['id_programa_estudio'], $data['id_ciclo'], $data['año']);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    throw new Exception('La sección ya está en uso.');
  }

  $stmt = $conn->prepare("INSERT INTO secciones (id_programa_estudio, id_ciclo, año) VALUES (?, ?, ?)");
  $stmt->bind_param('iis', $data['id_programa_estudio'], $data['id_ciclo'], $data['año']);

  if (!$stmt->execute()) {
    throw new Exception('Error al crear la sección: ' . $stmt->error);
  }

  echo json_encode(['success' => true, 'message' => 'Sección creada correctamente.']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
} finally {
  if (isset($stmt)) {
    $stmt->close();
  }
  if (isset($conn)) {
    $conn->close();
  }
}
