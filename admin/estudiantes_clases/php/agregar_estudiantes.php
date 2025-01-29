<?php
session_start();
require_once '../../../config/init.php';
require_once '../../../config/config.php';
require_once 'utils.php';

// Verificar autenticación y método POST
if (!isAuthenticated()) {
  responderJSON(false, 'No autorizado');
}
verificarMetodoPost();

$conn = conectarDB();

$data = json_decode(file_get_contents('php://input'), true);
$clase_id = isset($data['clase_id']) ? intval($data['clase_id']) : 0;
$estudiantes = isset($data['estudiantes']) ? $data['estudiantes'] : [];

if ($clase_id <= 0 || empty($estudiantes)) {
  responderJSON(false, 'Datos inválidos');
}

// Preparar la consulta para insertar estudiantes
$queryInsertEstudiantes = "INSERT INTO estudiantes_clases (id_estudiante, id_clase) VALUES (?, ?)";
$stmtInsertEstudiantes = $conn->prepare($queryInsertEstudiantes);

$conn->begin_transaction();

try {
  foreach ($estudiantes as $estudiante_id) {
    $stmtInsertEstudiantes->bind_param("ii", $estudiante_id, $clase_id);
    $stmtInsertEstudiantes->execute();
  }

  $conn->commit();
  responderJSON(true, 'Estudiantes agregados correctamente');
} catch (Exception $e) {
  $conn->rollback();
  responderJSON(false, 'Error al agregar estudiantes: ' . $e->getMessage());
}

$conn->close();