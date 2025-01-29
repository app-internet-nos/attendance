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

$stmt = $conn->prepare("SELECT id FROM ciclos WHERE nombre = ? AND id != ?");
$stmt->bind_param('si', $data['nombre'], $data['id']);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    responderJSON(false, 'El nombre ciclo ya está en uso.');
}

$stmt = $conn->prepare("UPDATE ciclos SET nombre = ?, descripcion = ? WHERE id = ?");
$stmt->bind_param('ssi', $data['nombre'], $data['descripcion'], $data['id']);

if ($stmt->execute()) {
    responderJSON(true, 'Ciclo actualizadoa correctamente.');
} else {
    responderJSON(false, 'Error al actualizar el ciclo.');
}

$stmt->close();
$conn->close();