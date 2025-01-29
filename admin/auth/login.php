<?php
require_once '../../config/init.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon">
  <title>Admin - Sistema de Asistencia</title>
  <link href="../../vendor/bootstrp5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../vendor/bootstrp5.3.0/css/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../../vendor/font-awesome-6.5.1/css/all.css">
  <!-- Agregar toastr CSS -->
  <link rel="stylesheet" href="../../vendor/toastr/toastr.min.css">
  
  <link rel="stylesheet" href="../../css/styles.css">
  
</head>

<body class="text-center">
  <main class="form-signin">
    <form id="loginForm" method="POST" action="auth.php" novalidate>
      <img src="../../img/admin_logo.png" alt="Logo Administrativo" class="img-fluid w-50 mb-2">
      <h4 class="mb-4 fw-medium text-uppercase">Iniciar sesión</h4>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="username" name="username" placeholder="Nombre de usuario" required autofocus>
        <label for="username">Nombre de usuario</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
        <label for="password">Contraseña</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Iniciar sesión <i class="fa-duotone fa-solid fa-arrow-right-to-bracket"></i></button>
      <p class="mt-3">
        <a href="../../index.php" class="text-muted text-decoration-none link-access"><i class="fa-duotone fa-solid fa-clock"></i> Marcar asistencia</a>
      </p>
    </form>
  </main>

  <script src="../../vendor/bootstrp5.3.0/js/bootstrap.bundle.min.js"></script>
  <!-- Agregar jQuery y toastr JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/toastr/toastr.min.js"></script>
  <script src="../../js/global-utils.js"></script>
  <script src="../../js/login-validation.js"></script>
</body>

</html>