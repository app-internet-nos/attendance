<?php
session_start();
require_once '../../config/init.php';
require_once '../../config/config.php';

// Verificar si el usuario está autenticado y es un estudiante
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'estudiante') {
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

// Obtener asistencias para la clase específica
$stmt = $conn->prepare("
    SELECT DATE(m.fecha_hora) as fecha,
           MAX(CASE WHEN m.tipo = 'entrada' THEN TIME(m.fecha_hora) END) as entrada,
           MAX(CASE WHEN m.tipo = 'salida' THEN TIME(m.fecha_hora) END) as salida,
           a.nombre AS asignatura, CONCAT(d.apellidos, ' ', d.nombres) AS docente 
    FROM marcados AS m
    JOIN estudiantes_clases AS ec ON m.id_estudiantes_clases = ec.id
    JOIN clases AS c ON ec.id_clase = c.id
    JOIN usuarios AS d ON c.id_docente = d.id
    JOIN asignaturas AS a ON c.id_asignatura = a.id
    WHERE ec.id_estudiante = ? AND c.id = ?
    GROUP BY DATE(m.fecha_hora), a.nombre, d.apellidos, d.nombres
    ORDER BY fecha DESC 
    LIMIT 30
");
$stmt->bind_param("ii", $user_id, $clase_id);
$stmt->execute();
$asistencias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$conn->close();

echo json_encode($asistencias);
