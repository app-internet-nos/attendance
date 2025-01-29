<?php
require_once __DIR__. '/../../../config/config.php';

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

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');