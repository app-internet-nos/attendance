<?php
session_start();
require_once 'utils.php';

verificarMetodoPost();

$conn = conectarDB();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
    responderJSON(false, 'ID de programa de estudio no proporcionado o inválido.');
}

$stmt = $conn->prepare("DELETE FROM programas_estudio WHERE id = ?");
$stmt->bind_param('i', $data['id']);

if ($stmt->execute()) {
    responderJSON(true, 'Programa de estudio eliminado correctamente.');
} else {
    responderJSON(false, 'Error al eliminar el programa de estudio.');
}

$stmt->close();
$conn->close();