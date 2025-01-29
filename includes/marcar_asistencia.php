<?php
define('INCLUDE_CHECK', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/init.php';

header('Content-Type: application/json');

function sendJsonResponse($success, $message, $redirect = null)
{
  $response = ['success' => $success, 'message' => $message];
  if ($redirect) {
    $response['redirect'] = $redirect;
  }
  echo json_encode($response);
  exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  sendJsonResponse(false, "Método no permitido");
}

if (!isset($_POST['dni']) || !isset($_POST['clase_id'])) {
  sendJsonResponse(false, "Error: Faltan datos necesarios");
}

$dni = $_POST['dni'];
$clase_id = $_POST['clase_id'];

try {
  $conn = conectarDB();

  // Verificar si el estudiante existe y obtener su ID
  $stmt = $conn->prepare("SELECT id FROM usuarios WHERE dni = ? AND role = 'estudiante'");
  $stmt->bind_param("s", $dni);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    sendJsonResponse(false, "Error: Estudiante no encontrado");
  }

  $estudiante = $result->fetch_assoc();
  $estudiante_id = $estudiante['id'];

  // Verificar si el estudiante está asignado a la clase
  $stmt = $conn->prepare("SELECT id FROM estudiantes_clases WHERE id_estudiante = ? AND id_clase = ?");
  $stmt->bind_param("ii", $estudiante_id, $clase_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    sendJsonResponse(false, "Error: El estudiante no está asignado a esta clase");
  }

  $estudiante_clase = $result->fetch_assoc();
  $estudiante_clase_id = $estudiante_clase['id'];

  // Verificar el último registro de asistencia
  $fecha_actual = date('Y-m-d');
  $stmt = $conn->prepare("SELECT tipo FROM marcados WHERE id_estudiantes_clases = ? AND DATE(fecha_hora) = ? ORDER BY fecha_hora DESC LIMIT 1");
  $stmt->bind_param("is", $estudiante_clase_id, $fecha_actual);
  $stmt->execute();
  $result = $stmt->get_result();

  // $tipo = ($result->num_rows === 0 || $result->fetch_assoc()['tipo'] === 'salida') ? 'entrada' : 'salida';

  if ($result->num_rows === 0) {
    $tipo = 'entrada';
  } else {
    unset($_SESSION['clases'], $_SESSION['dni']);
    // sendJsonResponse(false, 'Error al registrar la asistencia: ' . $conn->error, 'index.php');
    sendJsonResponse(false, 'Ya registro su asistencia', 'index.php');
  }

  // Insertar el nuevo registro de asistencia
  $fecha_hora = date('Y-m-d H:i:s');
  $stmt = $conn->prepare("INSERT INTO marcados (dni, fecha_hora, tipo, id_estudiantes_clases) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sssi", $dni, $fecha_hora, $tipo, $estudiante_clase_id);

  if ($stmt->execute()) {
    // Limpiar la sesión
    unset($_SESSION['clases'], $_SESSION['dni']);
    sendJsonResponse(true, "Asistencia registrada correctamente. Tipo: " . ucfirst($tipo) . " a las " . date('H:i:s'), 'index.php');
  } else {
    sendJsonResponse(false, "Error al registrar la asistencia: " . $conn->error);
  }
} catch (Exception $e) {
  sendJsonResponse(false, "Error en el servidor: " . $e->getMessage());
} finally {
  if (isset($conn)) {
    $conn->close();
  }
}
