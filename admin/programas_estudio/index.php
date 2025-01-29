<?php
require_once __DIR__ . '/../../config/config.php';


// Verify that the user is an administrator
if (!isAuthenticated() || $_SESSION['role'] !== ROLE_ADMIN) {
  header("Location: " . LOGIN_URL);
  exit();
}

$pageTitle = "Gestión de programas de estudio";

// Start of content
ob_start();
?>
<div class="container-fluid mt-2">
  <h3 class="mb-3 fw-semibold"><?php echo $pageTitle; ?></h3>
  <table id="dt" class="display table table-striped table-bordered" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Nombre corto</th>
      </tr>
    </thead>
    <tbody>
      <!-- Data will be loaded dynamically through DataTables -->
    </tbody>
    
  </table>
</div>
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