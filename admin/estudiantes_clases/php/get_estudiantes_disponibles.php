<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';

require_once 'utils.php';

// Verificar autenticación
if (!isAuthenticated()) {
  responderJSON(false, 'No autorizado');
}

$conn = conectarDB();

$clase_id = isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0;



if ($clase_id <= 0) {
  responderJSON(false, 'ID de clase no válido');
}

// Obtener estudiantes disponibles
$queryEstudiantesDisponibles = "SELECT e.id, e.dni, e.apellidos, e.nombres
                                FROM usuarios e
                                WHERE e.role = 'estudiante' AND e.id NOT IN (
                                  SELECT ec.id_estudiante 
                                  FROM estudiantes_clases ec 
                                  WHERE ec.id_clase = ?
                                )
                                ORDER BY e.apellidos, e.nombres";

$stmtEstudiantesDisponibles = $conn->prepare($queryEstudiantesDisponibles);
$stmtEstudiantesDisponibles->bind_param("i", $clase_id);
$stmtEstudiantesDisponibles->execute();
$resultEstudiantesDisponibles = $stmtEstudiantesDisponibles->get_result();

$estudiantes = [];
while ($estudiante = $resultEstudiantesDisponibles->fetch_assoc()) {
  $estudiantes[] = $estudiante;
}

responderJSON(true, '', ['estudiantes' => $estudiantes]);

$conn->close();