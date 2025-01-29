<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/init.php';

// Rutas de redirección
const REDIRECT_PATHS = [
  ROLE_ADMIN => '../dashboard/index.php',
  ROLE_DOCENTE => '../../docentes/dashboard/index.php',
  ROLE_ESTUDIANTE => '../../estudiantes/dashboard/index.php'
];

function validateInput($username, $password)
{
  if (empty($username) && empty($password)) {
    throw new Exception("Los campos nombre de usuario y password no pueden estar vacios.");
  } elseif (empty($username) && !empty($password)) {
    throw new Exception("El campo Nombre de usuario no puede estar vacío.");
  } elseif (!empty($username) && empty($password)) {
    throw new Exception("El campo Contraseña no puede estar vacío.");
  }
}

function authenticateUser($conn, $username, $password)
{
  $stmt = $conn->prepare("SELECT id, username, password, role, apellidos, nombres, foto, first_login FROM usuarios WHERE username = ? AND status = 'activo'");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if (!$user || !password_verify($password, $user['password'])) {
    throw new Exception("Nombre de usuario o contraseña incorrectos.");
  }
  return $user;
}

function setSessionData($user)
{
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['fullname'] = $user['nombres'] . ' ' . $user['apellidos'];
  $_SESSION['foto'] = $user['foto'];
  $_SESSION['role'] = $user['role'];
  $_SESSION['first_login'] = $user['first_login'];

  $userFoto = !empty($user['foto']) ? $user['foto'] : DEFAULT_PHOTO;
  $_SESSION['userFoto'] = $user['role'] . '/' . $userFoto;
}

function updateLastConnection($conn, $userId)
{
  $stmt = $conn->prepare("UPDATE usuarios SET ultima_conexion = CURRENT_TIMESTAMP WHERE id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
}

function redirectUser($role, $firstLogin)
{
  if ($firstLogin) {
    return '../../update_profile.php';
  }

  if (!isset(REDIRECT_PATHS[$role])) {
    throw new Exception("Rol de usuario no reconocido.");
  }
  return REDIRECT_PATHS[$role];
}

// Asegurarse de que siempre se devuelva una respuesta JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $response = ['success' => false, 'message' => '', 'redirect' => ''];

  try {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    validateInput($username, $password);

    $conn = conectarDB();
    $user = authenticateUser($conn, $username, $password);

    setSessionData($user);
    updateLastConnection($conn, $user['id']);

    $response['success'] = true;
    $response['message'] = 'Inicio de sesión exitoso.';
    $response['redirect'] = redirectUser($user['role'], $user['first_login']);
  } catch (Exception $e) {
    $response['message'] = $e->getMessage();
  } finally {
    if (isset($conn)) {
      $conn->close();
    }
  }

  echo json_encode($response);
  exit();
} else {
  echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
  exit();
}
