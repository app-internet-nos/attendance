<?php
session_start();
require_once 'utils.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    responderJSON(false, 'ID de unidad didáctica no proporcionado o inválido.');
}

$conn = conectarDB();

$stmt = $conn->prepare("SELECT id, nombre, descripcion FROM asignaturas WHERE id = ?");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $asignatura = $result->fetch_assoc();
    responderJSON(true, 'Datos obtenidos con éxito', $asignatura);
} else {
    responderJSON(false, 'Unidad didáctica no encontrada.');
}

$stmt->close();
$conn->close();