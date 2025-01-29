<?php
session_start();
require_once '../../../config/init.php';
require_once '../../../config/config.php';

// Verificar si el usuario está autenticado y es un docente
// Verificar autenticación
if (!isAuthenticated()) {
  echo json_encode(['success' => false, 'message' => 'No autorizado']);
  exit;
}

$conn = conectarDB();

$user_id = $_SESSION['user_id'];
$clase_id = isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0;

if ($clase_id <= 0) {
  echo json_encode(['success' => false, 'message' => 'ID de clase no válido']);
  exit();
}


// Obtener información de la clase
$queryClase = "SELECT c.id, a.nombre AS asignatura, CONCAT(d.apellidos, ', ', d.nombres) AS docente, 
                      CONCAT(pe.nombre, ' - ', ci.nombre, ' - ', s.año) AS seccion
               FROM clases c
               JOIN asignaturas a ON c.id_asignatura = a.id
               JOIN usuarios d ON c.id_docente = d.id
               JOIN secciones s ON c.id_seccion = s.id
               JOIN programas_estudio pe ON s.id_programa_estudio = pe.id
               JOIN ciclos ci ON s.id_ciclo = ci.id
               WHERE c.id = ? AND d.role='docente'";

$stmtClase = $conn->prepare($queryClase);
$stmtClase->bind_param("i", $clase_id);
$stmtClase->execute();
$resultClase = $stmtClase->get_result();
$clase = $resultClase->fetch_assoc();

if (!$clase) {
  echo json_encode(['success' => false, 'message' => 'Clase no encontrada']);
  exit;
}


// Obtener estudiantes de la clase
$queryEstudiantes = "SELECT e.id, e.dni, e.apellidos, e.nombres, e.email
                     FROM usuarios e
                     JOIN estudiantes_clases ec ON e.id = ec.id_estudiante
                     WHERE ec.id_clase = ? AND e.role='estudiante'
                     ORDER BY e.apellidos, e.nombres";

$stmtEstudiantes = $conn->prepare($queryEstudiantes);
$stmtEstudiantes->bind_param("i", $clase_id);
$stmtEstudiantes->execute();
$resultEstudiantes = $stmtEstudiantes->get_result();

$estudiantes = [];
while ($estudiante = $resultEstudiantes->fetch_assoc()) {
  $estudiantes[] = $estudiante;
}

// Preparar la respuesta
$response = [
  'success' => true,
  'clase' => [
    'id' => $clase['id'],
    'asignatura' => $clase['asignatura'],
    'docente' => $clase['docente'],
    'seccion' => $clase['seccion']
  ],
  'estudiantes' => $estudiantes
];

echo json_encode($response);

$conn->close();

