<!-- include/cargar_formulario.php  -->
<?php
session_start();
define('INCLUDE_CHECK', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/init.php';

$clases = $_SESSION['clases'] ?? [];

if (empty($clases)):
?>
  <form id="loginFormDni" method="POST" novalidate>
    <img src="img/marcado.png" alt="Logo" class="logo img-fluid mb-4" />
    <h2 class="mb-4">Marcar asistencia</h2>
    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="dni" name="dni" maxlength="8" placeholder="Ingresa DNI" required>
      <label for="dni">Ingresa DNI</label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Buscar Clases <i class="fa-duotone fa-solid fa-person-chalkboard"></i></button>
    <p class="mt-3">
      <a href="admin/auth/login.php" class="text-muted text-decoration-none link-access"><i class="fa-duotone fa-solid fa-arrow-right-from-bracket"></i> Acceso Administrativo</a>
    </p>
  </form>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('dni').focus();
    });
  </script>
<?php else: ?>
  <form id="loginFormClase" method="POST" novalidate>
    <img src="img/marcado.png" alt="Logo" class="logo img-fluid mb-4" />
    <h2 class="mb-4">Seleccionar Clase</h2>
    <div class="form-floating mb-3">
      <select class="form-select" id="clase_id" name="clase_id" required>
        <option value="" selected disabled>Selecciona una clase</option>
        <?php foreach ($clases as $clase): ?>
          <option value="<?= htmlspecialchars($clase['id']) ?>">
            <?= htmlspecialchars($clase['asignatura'] . ' - ' . $clase['docente'] . ' - ' . $clase['seccion']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <label for="clase_id">Clase</label>
    </div>
    <input type="hidden" name="dni" value="<?= htmlspecialchars($_SESSION['dni'] ?? '') ?>">
    <button class="w-100 btn btn-lg btn-primary mb-3" type="submit" name="marcar_asistencia">Marcar Asistencia</button>
    <p class="">
      <a id="btn-regresar" type="button" class="text-muted text-decoration-none link-access"><i class="fa-sharp fa-solid fa-rotate-left"></i> Regresar</a>
    </p>
  </form>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('clase_id').focus();
    });
  </script>

<?php endif; ?>