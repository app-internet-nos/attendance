<?php
session_start();
require_once '../../config/init.php';
require_once '../../config/config.php';

// Verificar si el usuario está autenticado y es un docente
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'docente') {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$user_id = $_SESSION['user_id'];
$clase_id = isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0;

if ($clase_id === 0) {
    echo json_encode(['error' => 'ID de clase no válido']);
    exit();
}

$conn = conectarDB();

// Verificar que la clase pertenezca al docente
$stmt = $conn->prepare("SELECT id FROM clases WHERE id = ? AND id_docente = ?");
$stmt->bind_param("ii", $clase_id, $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['error' => 'Clase no autorizada']);
    exit();
}

// Obtener todas las fechas en las que se ha registrado asistencia para esta clase
$stmt = $conn->prepare("
    SELECT DISTINCT DATE(m.fecha_hora) as fecha
    FROM marcados m
    JOIN estudiantes_clases ec ON m.id_estudiantes_clases = ec.id
    WHERE ec.id_clase = ? AND m.tipo = 'entrada'
    ORDER BY fecha
");
$stmt->bind_param("i", $clase_id);
$stmt->execute();
$fechas_result = $stmt->get_result();
$fechas = [];
while ($row = $fechas_result->fetch_assoc()) {
    $fechas[] = date('d/m', strtotime($row['fecha']));
}

// Obtener asistencias para la clase específica
$stmt = $conn->prepare("
    SELECT u.id, u.nombres, u.apellidos, 
           DATE(m.fecha_hora) as fecha,
           TIME(m.fecha_hora) as entrada
    FROM usuarios u
    JOIN estudiantes_clases ec ON u.id = ec.id_estudiante
    LEFT JOIN marcados m ON ec.id = m.id_estudiantes_clases AND m.tipo = 'entrada'
    WHERE ec.id_clase = ?
    ORDER BY u.apellidos, u.nombres, fecha
");
$stmt->bind_param("i", $clase_id);
$stmt->execute();
$asistencias_result = $stmt->get_result();

$estudiantes = [];
while ($row = $asistencias_result->fetch_assoc()) {
    if (!isset($estudiantes[$row['id']])) {
        $estudiantes[$row['id']] = [
            'nombres' => $row['nombres'],
            'apellidos' => $row['apellidos'],
            'asistencias' => []
        ];
    }
    if ($row['fecha']) {
        $fecha_formateada = date('d/m', strtotime($row['fecha']));
        $estudiantes[$row['id']]['asistencias'][$fecha_formateada] = $row['entrada'] ? substr($row['entrada'], 0, 5) : '--';
    }
}

$conn->close();

echo json_encode([
    'fechas' => $fechas,
    'estudiantes' => array_values($estudiantes)
]);
?>