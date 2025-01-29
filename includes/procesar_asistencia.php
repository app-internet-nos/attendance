<?php
// Definimos INCLUDE_CHECK aquí para evitar el error de acceso directo
define('INCLUDE_CHECK', true);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/init.php';

// Asegurarse de que siempre se envíe una respuesta JSON
header('Content-Type: application/json');

// Función para enviar respuesta JSON y terminar la ejecución
function sendJsonResponse($success, $message, $redirect = null)
{
  $response = ['success' => $success, 'message' => $message];
  if ($redirect) {
    $response['redirect'] = $redirect;
  }
  echo json_encode($response);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
  sendJsonResponse(false, 'Método no permitido');
}

$dni = trim($_POST['dni'] ?? '');
if (empty($dni)) {
  sendJsonResponse(false, 'DNI no proporcionado');
}

try {
  $conn = conectarDB();

  $stmt = $conn->prepare("SELECT id FROM usuarios WHERE dni = ? AND role = 'estudiante'");
  $stmt->bind_param("s", $dni);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    sendJsonResponse(false, 'Estudiante no encontrado');
  }

  $estudiante = $result->fetch_assoc();

  $query = "SELECT c.id, a.nombre AS asignatura, CONCAT(d.apellidos, ', ', d.nombres) AS docente, 
                     CONCAT(pe.nombre_corto, ' - ', ci.nombre, ' - ', s.año) AS seccion
              FROM estudiantes_clases ec
              JOIN clases c ON ec.id_clase = c.id
              JOIN asignaturas a ON c.id_asignatura = a.id
              JOIN usuarios d ON c.id_docente = d.id
              JOIN secciones s ON c.id_seccion = s.id
              JOIN programas_estudio pe ON s.id_programa_estudio = pe.id
              JOIN ciclos ci ON s.id_ciclo = ci.id
              WHERE ec.id_estudiante = ?
              ORDER BY pe.nombre_corto, ci.nombre, a.nombre";

  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $estudiante['id']);
  $stmt->execute();
  $clases = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  if (empty($clases)) {
    sendJsonResponse(false, 'No tienes clases asignadas');
  }

  $_SESSION['clases'] = $clases;
  $_SESSION['dni'] = $dni;

  sendJsonResponse(true, 'Clases encontradas', 'index.php');
} catch (Exception $e) {
  sendJsonResponse(false, 'Error en el servidor: ' . $e->getMessage());
} finally {
  if (isset($conn)) {
    $conn->close();
  }
}
