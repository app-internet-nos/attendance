<?php
require_once __DIR__ . '/../config/config.php';

if (!isAuthenticated() || !hasRole('admin')) {
  header("HTTP/1.1 403 Forbidden");
  exit(json_encode(['error' => 'Acceso denegado']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['estudiante_id'])) {
  header("HTTP/1.1 400 Bad Request");
  exit(json_encode(['error' => 'Solicitud inválida']));
}

$estudianteId = $_POST['estudiante_id'];

$conn = conectarDB();

$consultaClases = "SELECT c.id, a.nombre AS asignatura, CONCAT(d.apellidos, ', ', d.nombres) AS docente
                   FROM estudiantes_clases ec
                   JOIN clases c ON ec.id_clase = c.id
                   JOIN asignaturas a ON c.id_asignatura = a.id
                   JOIN usuarios d ON c.id_docente = d.id
                   WHERE ec.id_estudiante = ?
                   ORDER BY a.nombre";

$stmt = $conn->prepare($consultaClases);
$stmt->bind_param("i", $estudianteId);
$stmt->execute();
$resultado = $stmt->get_result();
$clases = $resultado->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($clases);
