<?php
// Incluir constantes globales
require_once __DIR__ . '/global_config.php';

// Incluir conexion base de datos
require_once __DIR__ . '/database.php';

// Incluir funciones globales
require_once __DIR__ . '/../includes/functions.php';

// Iniciar sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>