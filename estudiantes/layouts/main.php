<?php
require_once __DIR__ . '/../../config/config.php';

// Verificar autenticación
if (!isAuthenticated() || $_SESSION['role'] !== 'estudiante') {
  header("Location: " . LOGIN_URL);;
  exit();
}

require_once 'partials/head.php';
require "partials/nav.php";

?>

<div class="container mt-3">
  <?php include __DIR__ . '/../../admin/includes/messages.php'; ?>
  <?php echo $content; ?>
</div>

<?php
require_once 'partials/scripts.php';

// Scripts específicos de la página
echo $pageScripts ?? '';
echo $pageScriptsDt ?? '';
?>

</body>
</html>