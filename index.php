<!-- index.php -->
<?php
session_start();
define('INCLUDE_CHECK', true);
require_once 'config/config.php';
require_once 'config/init.php';

$mensaje = '';
if (isset($_SESSION['mensaje'])) {
  $mensaje = $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Marcar Asistencia</title>
  <link href="vendor/bootstrp5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="vendor/font-awesome-6.5.1/css/all.css">
  <link rel="stylesheet" href="vendor/toastr/css/toastr.min.css">
  <link rel="stylesheet" href="css/styles.css">
</head>

<body class="text-center">
  <main class="form-signin">
    <div id="reloj"><?php echo date('H:i:s'); ?></div>

    <div id="formContainer">
      <!-- El contenido del formulario se cargará aquí dinámicamente -->
    </div>
  </main>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/toastr/js/toastr.min.js"></script>
  <script src="js/global-utils.js"></script>
  <script src="js/index-validation.js"></script>
  <script>
    configureToastr({
      positionClass: "toast-top-center",
      timeOut: "1000"
    });

    function actualizarReloj() {
      const ahora = new Date();
      const horas = ahora.getHours().toString().padStart(2, '0');
      const minutos = ahora.getMinutes().toString().padStart(2, '0');
      const segundos = ahora.getSeconds().toString().padStart(2, '0');
      document.getElementById('reloj').innerHTML = `${horas}:${minutos}:${segundos}`;
    }
    setInterval(actualizarReloj, 1000);

    <?php if ($mensaje): ?>
      toastr.success('<?= addslashes($mensaje); ?>', 'Éxito');
    <?php endif; ?>

    // Start Carga del formulario 
    function cargarFormulario() {
      $.ajax({
        url: 'includes/cargar_formulario.php',
        type: 'GET',
        success: function(response) {
          $('#formContainer').html(response);
          if (window.initializeForm) {
            window.initializeForm();
          }
        },
        error: function() {
          toastr.error('Error al cargar el formulario');
        }
      });
    } // End Carga del formulario 

    $(document).ready(function() {
      cargarFormulario();
    });

    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>

</html>