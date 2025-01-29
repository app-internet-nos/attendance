<?php
require_once __DIR__ . '/../config/config.php';

if (!isAuthenticated() || !hasRole('admin')) {
  header("HTTP/1.1 403 Forbidden");
  exit(json_encode(['error' => 'Acceso denegado', 'type' => 'error']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit(json_encode(['error' => 'Método no permitido', 'type' => 'error']));
}

if (empty($_POST['clase_id']) || empty($_POST['fecha'])) {
  header("HTTP/1.1 400 Bad Request");
  exit(json_encode(['error' => 'Faltan datos requeridos', 'type' => 'warning']));
}

$claseId = $_POST['clase_id'];
$fecha = $_POST['fecha'];

if (!validateDate($fecha)) {
  header("HTTP/1.1 400 Bad Request");
  exit(json_encode(['error' => 'Formato de fecha inválido', 'type' => 'warning']));
}

$conn = conectarDB();

$consultaAsistencias = "
    SELECT 
        CONCAT(u.apellidos, ', ', u.nombres) AS estudiante,
        MAX(CASE WHEN m.tipo = 'entrada' THEN TIME(m.fecha_hora) END) AS hora_entrada,
        MAX(CASE WHEN m.tipo = 'salida' THEN TIME(m.fecha_hora) END) AS hora_salida
    FROM usuarios u
    JOIN estudiantes_clases ec ON u.id = ec.id_estudiante
    LEFT JOIN marcados m ON ec.id = m.id_estudiantes_clases AND DATE(m.fecha_hora) = ?
    WHERE ec.id_clase = ?
    GROUP BY u.id, u.apellidos, u.nombres
    ORDER BY u.apellidos, u.nombres
";

$stmt = $conn->prepare($consultaAsistencias);
$stmt->bind_param("si", $fecha, $claseId);
$stmt->execute();
$resultado = $stmt->get_result();
$asistencias = $resultado->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

// Procesar los datos y determinar el estado de asistencia
foreach ($asistencias as &$asistencia) {
  if ($asistencia['hora_entrada'] && $asistencia['hora_salida']) {
    $asistencia['estado'] = 'Presente';
  } elseif ($asistencia['hora_entrada']) {
    $asistencia['estado'] = 'Entrada sin salida';
  } elseif ($asistencia['hora_salida']) {
    $asistencia['estado'] = 'Salida sin entrada';
  } else {
    $asistencia['estado'] = 'Ausente';
  }
  $asistencia['hora_entrada'] = $asistencia['hora_entrada'] ?? '--:--';
  $asistencia['hora_salida'] = $asistencia['hora_salida'] ?? '--:--';
}

header('Content-Type: application/json');
echo json_encode([
  'data' => $asistencias,
  'message' => 'Datos cargados exitosamente',
  'type' => 'success'
]);
exit;

function validateDate($date, $format = 'Y-m-d')
{
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}
