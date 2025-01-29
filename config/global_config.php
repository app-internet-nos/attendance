<?php 

define('BASE_URL', '/');

// Definir la ruta base del proyecto
define('BASE_PATH', dirname(__DIR__));

// Definir la ruta del directorio de configuración
define('CONFIG_PATH', BASE_PATH . 'config');
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Definir la ruta del login
define('LOGIN_URL', BASE_URL . 'admin/auth/login.php');

// Definir roles
define('ROLE_ADMIN', 'admin');
define('ROLE_DOCENTE', 'docente');
define('ROLE_ESTUDIANTE', 'estudiante');
define('DEFAULT_PHOTO', 'default.png');
?>
