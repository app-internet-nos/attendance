<?php
session_start();
require_once 'utils.php';

verificarMetodoPost();

$conn = conectarDB();

$data = json_decode(file_get_contents('php://input'), true);
$data = validarEntrada($data);

if (empty($data['id']) || empty($data['nombre']) || empty($data['nombre_corto'])) {
    responderJSON(false, 'Todos los campos son obligatorios.');
}

$stmt = $conn->prepare("SELECT id FROM programas_estudio WHERE nombre = ? AND id != ?");
$stmt->bind_param('si', $data['nombre'], $data['id']);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    responderJSON(false, 'El nombre de programa de estudio ya está en uso.');
}

$stmt = $conn->prepare("UPDATE programas_estudio SET nombre = ?, nombre_corto = ? WHERE id = ?");
$stmt->bind_param('ssi', $data['nombre'], $data['nombre_corto'], $data['id']);

if ($stmt->execute()) {
    responderJSON(true, 'Programa de estudio actualizado correctamente.');
} else {
    responderJSON(false, 'Error al actualizar la Programa de estudio');
}

$stmt->close();
$conn->close();