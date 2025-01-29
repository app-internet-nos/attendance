<?php
session_start();
require_once 'config/config.php';
require_once 'config/init.php';

const REDIRECT_PATHS = [
  ROLE_ADMIN => '../dashboard/index.php',
  ROLE_DOCENTE => '../../docentes/dashboard/index.php',
  ROLE_ESTUDIANTE => '../../estudiantes/dashboard/index.php'
];

// Verificar si el usuario está logueado y es su primer inicio de sesión
if (!isset($_SESSION['user_id']) || !$_SESSION['first_login']) {
  header("Location: admin/auth/login.php");
  exit();
}

$userId = $_SESSION['user_id'];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = trim($_POST['email']);
  $dni = trim($_POST['dni']);

  // Validar email y DNI
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $mensaje = "Email no válido";
  } elseif (!preg_match("/^[0-9]{8}$/", $dni)) {
    $mensaje = "DNI no válido. Debe contener 8 dígitos.";
  } else {
    $conn = conectarDB();

    // Verificar si el email o DNI ya existen
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE (email = ? OR dni = ?) AND id != ?");
    $stmt->bind_param("ssi", $email, $dni, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $mensaje = "El email o DNI ya está en uso.";
    } else {
      // Actualizar perfil
      $stmt = $conn->prepare("UPDATE usuarios SET email = ?, dni = ?, first_login = FALSE WHERE id = ?");
      $stmt->bind_param("ssi", $email, $dni, $userId);

      if ($stmt->execute()) {
        $_SESSION['first_login'] = false;
        $_SESSION['mensaje'] = "Perfil actualizado correctamente.";

        // Redirigir al dashboard según el rol
        $redirect = REDIRECT_PATHS[$_SESSION['role']];
        header("Location: $redirect");
        exit();
      } else {
        $mensaje = "Error al actualizar el perfil: " . $conn->error;
      }
    }
    $conn->close();
  }
  $_SESSION['update_profile_error'] = $mensaje;
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// Recuperar mensaje de error de la sesión, si existe
if (isset($_SESSION['update_profile_error'])) {
  $mensaje = $_SESSION['update_profile_error'];
  unset($_SESSION['update_profile_error']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actualizar Perfil</title>
  <link href="vendor/bootstrp5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="vendor/font-awesome-6.5.1/css/all.css">
  <link rel="stylesheet" href="css/styles.css">
</head>

<body class="text-center">
  <main class="form-signin">
    <form method="POST" novalidate>
      <img src="img/admin_logo.png" alt="Logo" class="img-fluid w-50 mb-2">
      <h2 class="mb-4">Actualizar Perfil</h2>
      <p>Por favor, actualice su email y DNI para continuar.</p>

      <?php if ($mensaje): ?>
        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
      <?php endif; ?>

      <div class="form-floating mb-3">
        <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com" required>
        <label for="email">Email</label>
      </div>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="dni" name="dni" placeholder="12345678" required pattern="[0-9]{8}">
        <label for="dni">DNI</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Actualizar Perfil</button>
    </form>
  </main>

  <script src="vendor/bootstrp5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/toastr/toastr.min.js"></script>
  <script src="js/global-utils.js"></script>
</body>

</html>