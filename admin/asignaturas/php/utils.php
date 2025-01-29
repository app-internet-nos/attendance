<?php
require_once '../../../config/config.php';

function conectarDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    return $conn;
}

function responderJSON($success, $message = '', $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function validarEntrada($data) {
    if (!is_array($data)) {
        return [];
    }
    return array_map(function($item) {
        if (is_string($item)) {
            return htmlspecialchars(trim($item));
        }
        return $item;
    }, $data);
}

function verificarMetodoPost() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        responderJSON(false, 'Método de solicitud no permitido.');
    }
}