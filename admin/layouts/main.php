<?php
require_once __DIR__ . '/../../config/config.php';

if (!isAuthenticated() || !hasRole('admin')) {
  redirect(LOGIN_URL);
  exit();
}
?>

<?php

// Incluir <head>: styles generales e inica body
require_once 'partials/head.php';

// Incluir el Navbar picnipal
require_once 'partials/nav.php';


if (!isAuthenticated()) {
  redirect(LOGIN_URL);
}
?>


<div class="container mt-3">
  <?php include __DIR__ . '/../includes/messages.php'; ?>
  <?php echo $content; ?>
</div>

<?php

// Scripts generales de la página
require_once 'partials/scripts.php';

// Scripts específicos de la página
echo $pageScripts ?? '';
echo $pageScriptsDt ?? '';

?>

</body>

</html>