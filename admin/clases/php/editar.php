<?php
session_start();
require_once 'utils.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    responderJSON(false, 'ID de uclase no proporcionado o inválido.');
}

$conn = conectarDB();

$stmt = $conn->prepare("SELECT * FROM clases WHERE id = ?");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $seccion = $result->fetch_assoc();
    responderJSON(true, 'Datos obtenidos con éxito', $seccion);
} else {
    responderJSON(false, 'sección no encontrada.');
}

$stmt->close();
$conn->close();