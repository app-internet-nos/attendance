<?php
/**
 * Verifica si el usuario está autenticado
 *
 * @return boolean
 */
function isAuthenticated()
{
  return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Verifica si el usuario tiene un rol específico
 *
 * @param string $role El rol a verificar
 * @return boolean
 */
function hasRole($role)
{
  return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Redirige al usuario a una página específica
 *
 * @param string $page La página a la que redirigir
 */
function redirect($page)
{
  header("Location: $page");
  exit();
}

/**
 * Establece un mensaje en la sesión
 *
 * @param string $message El mensaje a establecer
 * @param string $type El tipo de mensaje (success, error, info, warning)
 */
function setMessage($message, $type = 'info')
{
  $_SESSION['mensaje'] = $message;
  $_SESSION['mensaje_tipo'] = $type;
}

/**
 * Escapa caracteres especiales en una cadena para uso en una sentencia SQL
 *
 * @param string $str La cadena a escapar
 * @return string La cadena escapada
 */
function escapeString($str)
{
  $conn = conectarDB();
  return $conn->real_escape_string($str);
}

/**
 * Genera un hash seguro de una contraseña
 *
 * @param string $password La contraseña a hashear
 * @return string El hash de la contraseña
 */
function hashPassword($password)
{
  return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verifica si una contraseña coincide con un hash
 *
 * @param string $password La contraseña a verificar
 * @param string $hash El hash con el que comparar
 * @return boolean
 */
function verifyPassword($password, $hash)
{
  return password_verify($password, $hash);
}


function setFlashMessage($type, $message)
{
  $_SESSION['flash_message'] = [
    'type' => $type,
    'message' => $message
  ];
}

function getFlashMessage()
{
  if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    return $message;
  }
  return null;
}

/**
 * Ruta fotos de los usuarios
 */

function getUploadUrl($path)
{
  return BASE_URL . 'uploads/' . $path;
}

/**
 * Ruta de 
 */
function getImgUrl($path)
{
  return BASE_URL . 'img/' . $path;
}
?>


<?php
function sanitize_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function generate_unique_filename($original_filename)
{
  $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
  return time() . '_' . rand(1000, 9999) . '.' . $extension;
}

function delete_file($filepath)
{
  if (file_exists($filepath) && is_file($filepath)) {
    unlink($filepath);
    return true;
  }
  return false;
}
?>