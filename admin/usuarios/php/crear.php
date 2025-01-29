<?php
session_start();
require_once '../../../config/config.php';

$conn = conectarDB();

// Comprueba si la solicitud es de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Captura los datos enviados en formato JSON.
  $data = json_decode(file_get_contents('php://input'), true);

  // Extrae los datos.
  $username = trim($data['username']);
  $password = trim($data['password']);
  $role = $data['role'];
  $status = $data['status'];
  $email = bin2hex(random_bytes(4)) . '@example.com';  // Aquí podrías usar un correo proporcionado por el usuario
  $apellidos = trim($data['apellidos']);
  $nombres = trim($data['nombres']);
  $dni = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

  // Validación básica.
  if (empty($username) || empty($password) || empty($apellidos) || empty($nombres)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit;
  }

  // Comprueba si el correo electrónico ya está registrado.
  $query = "SELECT id FROM usuarios WHERE username = ? OR email = ? OR dni = ?";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
    exit;
  }

  $stmt->bind_param('sss', $username, $email, $dni);
    $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El nombre de usuario, email o dni ya están en uso.']);
    exit;
  }

  // Hashea la contraseña.
  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  // Inserta el nuevo usuario en la base de datos.
  $query = "INSERT INTO usuarios (username, password, role, status, email, apellidos, nombres, dni) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
    exit;
}
  $stmt->bind_param('ssssssss', $username, $hashed_password, $role, $status, $email, $apellidos, $nombres, $dni);

  if ($stmt->execute()) {
    // Respuesta exitosa.
    echo json_encode(['success' => true]);
  } else {
    // Respuesta de error en la inserción.
    echo json_encode(['success' => false, 'message' => 'Error al crear el usuario.']);
  }

  $stmt->close();
  $conn->close();
} else {
  // Si no es una solicitud POST, responde con un error.
  echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
