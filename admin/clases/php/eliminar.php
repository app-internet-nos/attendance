<?php
session_start();
require_once 'utils.php';

verificarMetodoPost();

$conn = conectarDB();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
    responderJSON(false, 'ID de la clase no proporcionado o inválido.');
}

$stmt = $conn->prepare("DELETE FROM clases WHERE id = ?");
$stmt->bind_param('i', $data['id']);

if ($stmt->execute()) {
    responderJSON(true, 'Clase eliminada correctamente.');
} else {
    responderJSON(false, 'Error al eliminar la clasess.');
}

$stmt->close();
$conn->close();