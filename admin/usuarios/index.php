<?php
require_once __DIR__ . '/../../config/config.php';

// Verificar que el usuario es un administrador
if (!isAuthenticated() || $_SESSION['role'] !== ROLE_ADMIN) {
  header("Location: " . LOGIN_URL);
  exit();
}

$pageTitle = "Gestión de Usuarios";

// Iniciar la captura de salida
ob_start();
?>

<div class="container-fluid mt-2">
  <h2 class="mb-4"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h2>

  <table id="dt" class="display table table-striped table-bordered" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Apellidos</th>
        <th>Nombres</th>
        <th>Rol</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <!-- Los datos se cargarán dinámicamente a través de DataTables -->
    </tbody>
  </table>
</div>

<?php
$content = ob_get_clean();

// Scripts necesarios para DataTables
$pageStyles = '';
$pageScripts = <<<SCRIPT
    <script src="ajax/index.js"></script>
    <script src="ajax/index-dt.js"></script>
SCRIPT;

require_once '../layouts/main.php';
?>