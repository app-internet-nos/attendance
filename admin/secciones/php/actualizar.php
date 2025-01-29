<?php
session_start();
require_once 'utils.php';

verificarMetodoPost();

$conn = conectarDB();

$data = json_decode(file_get_contents('php://input'), true);
$data = validarEntrada($data);

if (empty($data['id']) || empty($data['id_programa_estudio']) || empty($data['id_ciclo']) || empty($data['año'])) {
    responderJSON(false, 'Todos los campos son obligatorios.');
}

 // Consulta para verificar si la seccion a existe
 $stmt = $conn->prepare("SELECT * FROM secciones WHERE id_programa_estudio = ? AND id_ciclo = ? AND año = ? AND id !=?");
 $stmt->bind_param('iisi', $data['id_programa_estudio'], $data['id_ciclo'], $data['año'], $data['id']);
 $stmt->execute();
 $stmt->store_result();
 if ($stmt->num_rows > 0) {
   throw new Exception('La sección ya está en uso.');
 }

$stmt = $conn->prepare("UPDATE secciones SET id_programa_estudio = ?, id_ciclo = ?, año = ? WHERE id = ?");
$stmt->bind_param('iisi', $data['id_programa_estudio'], $data['id_ciclo'], $data['año'] , $data['id']);

if ($stmt->execute()) {
    responderJSON(true, 'Sección actualizada correctamente.');
} else {
    responderJSON(false, 'Error al actualizar la unidad didáctica.');
}

$stmt->close();
$conn->close();