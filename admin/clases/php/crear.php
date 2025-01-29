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

  if (empty($data['id_asignatura']) || empty($data['id_docente']) || empty($data['id_seccion'])) {
    throw new Exception('Todos los campos son obligatorios.');
  }

  // Consulta para verificar si la seccion a existe
  $stmt = $conn->prepare("SELECT * FROM clases WHERE id_asignatura = ? AND id_docente = ? AND id_seccion = ?");
  $stmt->bind_param('iii', $data['id_asignatura'], $data['id_docente'], $data['id_seccion']);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    throw new Exception('La clase ya está en uso.');
  }

  $stmt = $conn->prepare("INSERT INTO clases (id_asignatura, id_docente, id_seccion) VALUES (?, ?, ?)");
  $stmt->bind_param('iii', $data['id_asignatura'], $data['id_docente'], $data['id_seccion']);

  if (!$stmt->execute()) {
    throw new Exception('Error al crear la clase: ' . $stmt->error);
  } 

  echo json_encode(['success' => true, 'message' => 'Clase creada correctamente.']);
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
