<?php
session_start();
require_once '../../../config/config.php';

$conn = conectarDB();

// Obtener los datos del POST
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que el ID ha sido proporcionado y es válido
if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado o inválido']);
    exit;
}

$id = (int) $data['id']; // Asegurarse de que el ID sea un número entero

// Preparar la consulta para eliminar el usuario
$query = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
if (!$query) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta']);
    exit;
}

$query->bind_param('i', $id);

if ($query->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario']);
}

$query->close();
$conn->close();
?>
