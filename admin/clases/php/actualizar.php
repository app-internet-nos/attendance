<?php
session_start();
require_once 'utils.php';

verificarMetodoPost();

$conn = conectarDB();

$data = json_decode(file_get_contents('php://input'), true);
$data = validarEntrada($data);

if (empty($data['id']) || empty($data['id_asignatura']) || empty($data['id_docente']) || empty($data['id_seccion'])) {
    responderJSON(false, 'Todos los campos son obligatorios.');
}

 // Consulta para verificar si la seccion a existe
 $stmt = $conn->prepare("SELECT * FROM clases WHERE id_asignatura = ? AND id_docente = ? AND id_seccion = ? AND id !=?");
 $stmt->bind_param('iiii', $data['id_asignatura'], $data['id_docente'], $data['id_seccion'], $data['id']);
 $stmt->execute();
 $stmt->store_result();
 if ($stmt->num_rows > 0) {
   throw new Exception('La clase ya está en uso.');
 }

$stmt = $conn->prepare("UPDATE clases SET id_asignatura = ?, id_docente = ?, id_seccion = ? WHERE id = ?");
$stmt->bind_param('iiii', $data['id_asignatura'], $data['id_docente'], $data['id_seccion'] , $data['id']);

if ($stmt->execute()) {
    responderJSON(true, 'Clase actualizada correctamente.');
} else {
    responderJSON(false, 'Error al actualizar la clase.');
}

$stmt->close();
$conn->close();