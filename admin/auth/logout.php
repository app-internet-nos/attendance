<?php
// Iniciar la sesión si no está iniciada
session_start();

// Desestablecer todas las variables de sesión
$_SESSION = array();

// Si se está usando un cookie de sesión, destruirlo
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir a la página de inicio de sesión
header("Location: login.php");
exit();
?>