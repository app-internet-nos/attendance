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
if (empty($_POST['clase_id']) || empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin'])) {
  header("HTTP/1.1 400 Bad Request");
  $camposFaltantes = [];
  if (empty($_POST['clase_id'])) $camposFaltantes[] = 'clase_id';
  if (empty($_POST['fecha_inicio'])) $camposFaltantes[] = 'fecha_inicio';
  if (empty($_POST['fecha_fin'])) $camposFaltantes[] = 'fecha_fin';
  exit(json_encode([
    'error' => 'Faltan datos requeridos: ' . implode(', ', $camposFaltantes),
    'type' => 'warning'
  ]));
}


$claseId = $_POST['clase_id'];
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
$defaultTimezone = date_default_timezone_get(); // Guardar la zona horaria actual
date_default_timezone_set('America/Lima');

// Obtener la lista de fechas
$consultaFechas = "
    SELECT DISTINCT DATE(fecha_hora) as fecha
    FROM marcados m
    JOIN estudiantes_clases ec ON m.id_estudiantes_clases = ec.id
    WHERE ec.id_clase = ? AND DATE(m.fecha_hora) BETWEEN ? AND ?
    ORDER BY fecha
    ";

$stmtFechas = $conn->prepare($consultaFechas);
$stmtFechas->bind_param("iss", $claseId, $fechaInicio, $fechaFin);
$stmtFechas->execute();
$resultadoFechas = $stmtFechas->get_result();
$fechas = $resultadoFechas->fetch_all(MYSQLI_ASSOC);
$stmtFechas->close();

// Obtener los datos de asistencia
$consultaAsistencias = "
    SELECT 
        e.id as estudiante_id,
        e.apellidos, 
        e.nombres, 
        DATE(m.fecha_hora) AS fecha,
        MAX(CASE WHEN m.tipo = 'entrada' THEN TIME(m.fecha_hora) END) AS hora_entrada,
        MAX(CASE WHEN m.tipo = 'salida' THEN TIME(m.fecha_hora) END) AS hora_salida
    FROM usuarios e
    JOIN estudiantes_clases ec ON e.id = ec.id_estudiante
    LEFT JOIN marcados m ON ec.id = m.id_estudiantes_clases AND DATE(m.fecha_hora) BETWEEN ? AND ?
    WHERE ec.id_clase = ?
    GROUP BY e.id, e.apellidos, e.nombres, DATE(m.fecha_hora)
    ORDER BY e.apellidos, e.nombres, fecha
    ";

$stmtAsistencias = $conn->prepare($consultaAsistencias);
$stmtAsistencias->bind_param("ssi", $fechaInicio, $fechaFin, $claseId);
$stmtAsistencias->execute();
$resultadoAsistencias = $stmtAsistencias->get_result();
$asistencias = $resultadoAsistencias->fetch_all(MYSQLI_ASSOC);
$stmtAsistencias->close();

$conn->close();



// Procesar los datos
$estudiantesPorId = [];
foreach ($asistencias as $asistencia) {
  $id = $asistencia['estudiante_id'];
  if (!isset($estudiantesPorId[$id])) {
    $estudiantesPorId[$id] = [
      'id' => $id,
      'estudiante' => $asistencia['apellidos'] . ', ' . $asistencia['nombres']
    ];
    // Inicializar todas las fechas con un valor por defecto
    foreach ($fechas as $fecha) {
      $estudiantesPorId[$id][$fecha['fecha']] = 'E:-- S:--';
    }
  }
  $fecha = $asistencia['fecha'];
  $estudiantesPorId[$id][$fecha] = ($asistencia['hora_entrada'] ? 'E:' . $asistencia['hora_entrada'] : 'E:--') .
    ' ' .
    ($asistencia['hora_salida'] ? 'S:' . $asistencia['hora_salida'] : 'S:--');
}

// Preparar los datos para DataTables
$datosTabla = array_values($estudiantesPorId);
$columnas = [
  ['title' => 'ID', 'data' => 'id'],
  ['title' => 'Estudiante', 'data' => 'estudiante']
];
foreach ($fechas as $fecha) {
  $fechaObj = new DateTime($fecha['fecha']);
  $fechaFormateada = $fechaObj->format('d/m');
  $columnas[] = [
    'title' => $fechaFormateada,
    'data' => $fecha['fecha']
  ];
}

// Restaurar la zona horaria original
date_default_timezone_set($defaultTimezone);

header('Content-Type: application/json');
echo json_encode([
  'data' => $datosTabla,
  'columns' => $columnas
]);
exit;
// } 

// else {
//   header("HTTP/1.1 405 Method Not Allowed");
//   exit(json_encode(['error' => 'Método no permitido']));
// }


// Al final, si todo va bien:
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
