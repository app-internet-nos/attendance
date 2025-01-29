<?php
session_start();
require_once 'utils.php';

verificarMetodoPost();

$conn = conectarDB();

$data = json_decode(file_get_contents('php://input'), true);
$data = validarEntrada($data);

if (empty($data['id']) || empty($data['nombre']) || empty($data['descripcion'])) {
    responderJSON(false, 'Todos los campos son obligatorios.');
}

$stmt = $conn->prepare("SELECT id FROM asignaturas WHERE nombre = ? AND id != ?");
$stmt->bind_param('si', $data['nombre'], $data['id']);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    responderJSON(false, 'El nombre de la unidad didáctica ya está en uso.');
}

$stmt = $conn->prepare("UPDATE asignaturas SET nombre = ?, descripcion = ? WHERE id = ?");
$stmt->bind_param('ssi', $data['nombre'], $data['descripcion'], $data['id']);

if ($stmt->execute()) {
    responderJSON(true, 'Unidad didáctica actualizada correctamente.');
} else {
    responderJSON(false, 'Error al actualizar la unidad didáctica.');
}

$stmt->close();
$conn->close();