<?php
require_once 'procesar_perfil.php';
$pageTitle = "Perfil del docente";
ob_start();
?>
<div class="container">

  <h1>Test</h1>

</div>

<?php
$content = ob_get_clean();


include __DIR__ . '/../layouts/main.php';
?>
