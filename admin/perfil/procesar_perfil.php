<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Asegurarse de que solo los docentes puedan acceder a esta página
if ($_SESSION['role'] !== 'admin') {
  header("Location: " . LOGIN_URL);
  exit();
}

$conn = conectarDB();
$userId = $_SESSION['user_id'];

// Obtener los datos del usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Función para establecer mensajes Toastr
function setToastrMessage($type, $message)
{
  $_SESSION['toastr'] = [
    'type' => $type,
    'message' => $message
  ];
}

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update_profile'])) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $genero = $_POST['genero'];

    // Validar los datos
    if (empty($nombres) || empty($apellidos) || empty($email)) {
      setToastrMessage('error', "Todos los campos son obligatorios.");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      setToastrMessage('error', "El email no es válido.");
    } else {
      // Manejar la subida de la foto de perfil
      $foto = $user['foto'];
      if (!empty($_FILES['foto']['name'])) {
        $uploadDir = __DIR__ . '/../../uploads/admin/';
        $fileExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $newFileName = time() . '_' . rand(1000, 9999) . '.' . $fileExtension;
        $uploadFile = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
          // Si hay una foto anterior, la eliminamos
          if ($user['foto'] && $user['foto'] !== 'default.png') {
            unlink($uploadDir . $user['foto']);
          }
          $foto = $newFileName;

          // Actualizar la sesión con la nueva foto
          $_SESSION['foto'] = $foto;
          $_SESSION['userFoto'] = 'admin/' . $foto;
        } else {
          setToastrMessage('error', "Error al subir la foto de perfil.");
        }
      }

      // Actualizar los datos del usuario
      $stmt = $conn->prepare("UPDATE usuarios SET nombres = ?, apellidos = ?, email = ?, genero = ?, foto = ? WHERE id = ?");
      $stmt->bind_param("sssssi", $nombres, $apellidos, $email, $genero, $foto, $userId);
      if ($stmt->execute()) {
        setToastrMessage('success', "Perfil actualizado correctamente.");
        // Actualizar los datos en la sesión
        $_SESSION['fullname'] = $nombres . ' ' . $apellidos;

        // Redireccionar para evitar reenvío del formulario
        header("Location: " . $_SERVER['PHP_SELF']);

        exit();
      } else {
        setToastrMessage('error', "Error al actualizar el perfil: " . $conn->error);
      }
      $stmt->close();
    }
  } if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $response = ['success' => false, 'message' => ''];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
      $response['message'] = "Todos los campos son obligatorios.";
    } elseif (!password_verify($current_password, $user['password'])) {
      $response['message'] = "La contraseña actual es incorrecta.";
    } elseif ($new_password !== $confirm_password) {
      $response['message'] = "Las nuevas contraseñas no coinciden.";
    } elseif (strlen($new_password) < 8) {
      $response['message'] = "La nueva contraseña debe tener al menos 8 caracteres.";
    } else {
      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
      $stmt->bind_param("si", $hashed_password, $userId);
      if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Contraseña actualizada correctamente.";
      } else {
        $response['message'] = "Error al actualizar la contraseña: " . $conn->error;
      }
      $stmt->close();
    }

    // Si es una solicitud AJAX, devolver JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      header('Content-Type: application/json');
      echo json_encode($response);
      exit;
    } else {
      // Si no es AJAX, establecer el mensaje Toastr y redirigir
      setToastrMessage($response['success'] ? 'success' : 'error', $response['message']);
      header("Location: " . $_SERVER['PHP_SELF'] . '#password-tab-pane');
      exit();
    }
  }
}

// Preparar el mensaje Toastr si existe
$toastrScript = '';

if (isset($_SESSION['toastr'])) {
  $type = $_SESSION['toastr']['type'];
  $message = addslashes($_SESSION['toastr']['message']); // Escapar comillas simples
  $toastrScript = "toastr.$type('$message');";
  unset($_SESSION['toastr']);
}