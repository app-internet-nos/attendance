<?php
require_once __DIR__ . '/../../config/config.php';

// Verify that the user is an administrator
if (!isAuthenticated() || $_SESSION['role'] !== ROLE_ADMIN) {
  header("Location: ../../admin/auth/login.php");
  exit();
}

$pageTitle = "Gestión de clases";

// Start of content
ob_start();
?>
<h2 class="mb-4"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h2>

<table id="dt" class="display table table-striped table-bordered" style="width:100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>Unidad didáctica</th>
      <th>Programa de estudio</th>
      <th>Ciclo</th>
      <th>Año</th>
      <th>Docente</th>
    </tr>
  </thead>
  <tbody>
    <!-- Data will be loaded dynamically through DataTables -->
  </tbody>
  
</table>

<?php
$content = ob_get_clean();

// Unified scripts
$pageScripts = '
<script src="ajax/index.js"></script>
<script src="ajax/index-dt.js"></script>
';

// You can keep styles separate if needed, or include them in $pageScripts if they're always used together
$pageStyles = '';

require_once '../layouts/main.php';
?>