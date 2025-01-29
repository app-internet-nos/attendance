<?php
session_start();
require_once '../../../config/config.php';

$conn = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura los datos enviados en formato JSON.
    $data = json_decode(file_get_contents('php://input'), true);

    // Extrae los datos.
    $id = $data['id'];
    $username = trim($data['username']);
   
    $role = $data['role'];
    $status = $data['status'];
    $apellidos = trim($data['apellidos']);
    $nombres = trim($data['nombres']);

    // Validación básica.
    if (empty($username) || empty($apellidos) || empty($nombres)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

        // Verifica si el nombre de usuario o el correo ya existen en otro usuario.
    $query = "SELECT id FROM usuarios WHERE (username = ? OR email = ?) AND id != ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
        exit;
    }
    
    $stmt->bind_param('ssi', $username, $email, $id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario o el correo electrónico ya están en uso por otro usuario.']);
        $stmt->close();
        exit;
    }

    // Actualiza los datos del usuario en la base de datos.

    $query = "UPDATE usuarios SET username = ?, role = ?, status = ?, apellidos = ?, nombres = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
      echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de actualización.']);
      exit;
  }

    $stmt->bind_param('sssssi', $username, $role, $status, $apellidos, $nombres, $id);

    if ($stmt->execute()) {
        // Respuesta exitosa.
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
    } else {
        // Respuesta de error en la actualización.
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario.']);
    }

    $stmt->close();
    $conn->close();
} else {
    // Si no es una solicitud POST, responde con un error.
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
