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

// Validación mejorada de los datos POST
if (empty($_POST['estudiante_id']) || empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin'])) {
  header("HTTP/1.1 400 Bad Request");
  $camposFaltantes = [];
  if (empty($_POST['estudiante_id'])) $camposFaltantes[] = 'estudiante_id';
  if (empty($_POST['fecha_inicio'])) $camposFaltantes[] = 'fecha_inicio';
  if (empty($_POST['fecha_fin'])) $camposFaltantes[] = 'fecha_fin';
  exit(json_encode([
    'error' => 'Faltan datos requeridos: ' . implode(', ', $camposFaltantes),
    'type' => 'warning'
  ]));
}

$estudianteId = $_POST['estudiante_id'];
$fechaInicio = $_POST['fecha_inicio'];
$fechaFin = $_POST['fecha_fin'];

// Validación adicional de fechas
if (!validateDate($fechaInicio) || !validateDate($fechaFin)) {
  header("HTTP/1.1 400 Bad Request");
  exit(json_encode(['error' => 'Formato de fecha inválido', 'type' => 'warning']));
}

if (strtotime($fechaFin) < strtotime($fechaInicio)) {
  header("HTTP/1.1 400 Bad Request");
  exit(json_encode(['error' => 'La fecha de fin debe ser posterior a la fecha de inicio', 'type' => 'warning']));
}

$conn = conectarDB();

// Configurar la zona horaria para Perú
$defaultTimezone = date_default_timezone_get();
date_default_timezone_set('America/Lima');

// Obtener los datos de asistencia
$consultaAsistencias = "
    SELECT 
        a.nombre AS asignatura,
        DATE(m.fecha_hora) AS fecha,
        MAX(CASE WHEN m.tipo = 'entrada' THEN TIME(m.fecha_hora) END) AS hora_entrada,
        MAX(CASE WHEN m.tipo = 'salida' THEN TIME(m.fecha_hora) END) AS hora_salida
    FROM marcados m
    JOIN estudiantes_clases ec ON m.id_estudiantes_clases = ec.id
    JOIN clases c ON ec.id_clase = c.id
    JOIN asignaturas a ON c.id_asignatura = a.id
    WHERE ec.id_estudiante = ? AND DATE(m.fecha_hora) BETWEEN ? AND ?
    GROUP BY a.id, DATE(m.fecha_hora)
    ORDER BY a.nombre, fecha
";

$stmtAsistencias = $conn->prepare($consultaAsistencias);
$stmtAsistencias->bind_param("iss", $estudianteId, $fechaInicio, $fechaFin);
$stmtAsistencias->execute();
$resultadoAsistencias = $stmtAsistencias->get_result();
$asistencias = $resultadoAsistencias->fetch_all(MYSQLI_ASSOC);
$stmtAsistencias->close();

$conn->close();

// Procesar los datos
$asistenciasPorAsignatura = [];
$fechas = [];
foreach ($asistencias as $asistencia) {
  $asignatura = $asistencia['asignatura'];
  $fecha = $asistencia['fecha'];
  if (!isset($asistenciasPorAsignatura[$asignatura])) {
    $asistenciasPorAsignatura[$asignatura] = ['asignatura' => $asignatura];
  }
  $asistenciasPorAsignatura[$asignatura][$fecha] = 
    ($asistencia['hora_entrada'] ? 'E:' . $asistencia['hora_entrada'] : 'E:--') . ' ' .
    ($asistencia['hora_salida'] ? 'S:' . $asistencia['hora_salida'] : 'S:--');
  if (!in_array($fecha, $fechas)) {
    $fechas[] = $fecha;
  }
}

// Preparar los datos para DataTables
$datosTabla = array_values($asistenciasPorAsignatura);
$columnas = [
  ['title' => 'Asignatura', 'data' => 'asignatura']
];
sort($fechas);
foreach ($fechas as $fecha) {
  $fechaObj = new DateTime($fecha);
  $fechaFormateada = $fechaObj->format('d/m');
  $columnas[] = [
    'title' => $fechaFormateada,
    'data' => $fecha
  ];
}

// Restaurar la zona horaria original
date_default_timezone_set($defaultTimezone);

header('Content-Type: application/json');
echo json_encode([
  'data' => $datosTabla,
  'columns' => $columnas,
  'message' => 'Datos cargados exitosamente',
  'type' => 'success'
]);
exit;

function validateDate($date, $format = 'Y-m-d')
{
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}
?>