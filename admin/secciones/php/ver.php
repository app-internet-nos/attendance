<?php
session_start();
require_once 'utils.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    responderJSON(false, 'ID de la sección no proporcionado o inválido.');
}

$conn = conectarDB();

$stmt = $conn->prepare("SELECT * FROM secciones WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $seccion = $result->fetch_assoc();
    responderJSON(true, 'Datos obtenidos con éxito', $seccion);
} else {
    responderJSON(false, 'Sección no encontrada.');
}

$stmt->close();
$conn->close();