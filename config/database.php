<?php
if (!defined('DB_HOST')) {
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'attendance');
}

if (!function_exists('conectarDB')) {
  function conectarDB()
  {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
      die("Conexión fallida: " . $conn->connect_error);
    }
    // Establecer el conjunto de caracteres a utf8mb4
    if (!$conn->set_charset("utf8mb4")) {
      die("Error cargando el conjunto de caracteres utf8mb4: " . $conn->error);
    }
    return $conn;
  }
}
