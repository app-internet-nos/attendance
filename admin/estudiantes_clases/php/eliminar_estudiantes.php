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

// Preparar la consulta para eliminar estudiantes
$queryEliminarEstudiantes = "DELETE FROM estudiantes_clases WHERE id_clase = ? AND id_estudiante IN (SELECT id FROM usuarios WHERE dni = ?)";
$stmtEliminarEstudiantes = $conn->prepare($queryEliminarEstudiantes);

$conn->begin_transaction();

try {
  foreach ($estudiantes as $dni) {
    $stmtEliminarEstudiantes->bind_param("is", $clase_id, $dni);
    $stmtEliminarEstudiantes->execute();
  }

  $conn->commit();
  responderJSON(true, 'Estudiantes eliminados correctamente');
} catch (Exception $e) {
  $conn->rollback();
  responderJSON(false, 'Error al eliminar estudiantes: ' . $e->getMessage());
}

$conn->close();