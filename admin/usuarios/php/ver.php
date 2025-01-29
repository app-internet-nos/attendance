<?php
require_once '../../../config/config.php';
require_once '../../includes/conexion.php';

$conn = conectarDB();

// Verificar que se haya pasado un ID y que sea válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado o inválido']);
    exit;
}

$id = (int) $_GET['id']; // Asegurarse de que el ID sea un número entero

// Preparar la consulta SQL
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ? LIMIT 1");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta']);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Retornar los datos en formato JSON
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}

$stmt->close();
$conn->close();
