<?php
session_start();
require_once 'utils.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', '0');

try {
    verificarMetodoPost();

    $conn = conectarDB();

    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON de entrada: ' . json_last_error_msg());
    }

    $data = validarEntrada($data);

    if (empty($data['nombre']) || empty($data['descripcion'])) {
        throw new Exception('Todos los campos son obligatorios.');
    }

    $stmt = $conn->prepare("SELECT id FROM ciclos WHERE nombre = ?");
    $stmt->bind_param('s', $data['nombre']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        throw new Exception('El nombre de ciclos ya está en uso.');
    }

    $stmt = $conn->prepare("INSERT INTO ciclos (nombre, descripcion) VALUES (?, ?)");
    $stmt->bind_param('ss', $data['nombre'], $data['descripcion']);

    if (!$stmt->execute()) {
        throw new Exception('Error al crear el ciclo: ' . $stmt->error);
    }

    echo json_encode(['success' => true, 'message' => 'Ciclo creado correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}