<?php
session_start();
require_once 'utils.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    responderJSON(false, 'ID de programa de estudio no proporcionado o inválido.');
}

$conn = conectarDB();

$stmt = $conn->prepare("SELECT id, nombre, nombre_corto FROM programas_estudio WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $programa_estudio = $result->fetch_assoc();
    responderJSON(true, 'Datos obtenidos con éxito', $programa_estudio);
} else {
    responderJSON(false, 'Programa de estudio no encontrado.');
}

$stmt->close();
$conn->close();