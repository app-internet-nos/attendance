<?php
require_once __DIR__ . '/../config/config.php';

if (!isAuthenticated() || !hasRole('admin')) {
  redirect(LOGIN_URL);
  exit();
}

$pageTitle = "Resumen de Asistencias por Clase";
$pageTitleAsistencia = "Resumen de Asistencias";

$conn = conectarDB();
// Obtener lista de clases para el select
$consultaClases = "SELECT c.id, a.nombre AS asignatura, CONCAT(u.apellidos, ', ', u.nombres) AS docente,
                          CONCAT(pe.nombre_corto, ' - ', ci.nombre, ' - ', s.año) AS seccion
                   FROM clases c
                   JOIN asignaturas a ON c.id_asignatura = a.id
                   JOIN usuarios u ON c.id_docente = u.id
                   JOIN secciones s ON c.id_seccion = s.id
                   JOIN programas_estudio pe ON s.id_programa_estudio = pe.id
                   JOIN ciclos ci ON s.id_ciclo = ci.id
                   ORDER BY a.nombre, u.apellidos, u.nombres";

$resultadoClases = $conn->query($consultaClases);
$clases = $resultadoClases->fetch_all(MYSQLI_ASSOC);

$conn->close();

ob_start();
?>

<div class="card">
  <h3 class="card-header fw-semibold"> <span class="mx-3"><?= htmlspecialchars($pageTitle); ?></span></h3>
  <div class="card-body mx-3">
    <form id="formularioResumen" novalidate>
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="clase_id" class="mb-2 fw-semibold">Seleccionar Clase:</label>
            <select class="form-select" id="clase_id" name="clase_id" required>
              <option value="">Seleccione una clase</option>
              <?php foreach ($clases as $clase): ?>
                <option value="<?= $clase['id']; ?>">
                  <?= htmlspecialchars($clase['asignatura'] . ' - ' . $clase['docente'] . ' - ' . $clase['seccion']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="fecha" class="mb-2 fw-semibold">Fecha:</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer text-center">
    <span class="mx-3"><button type="submit" form="formularioResumen" class="btn btn-primary">Generar Resumen</button></span>
  </div>
</div>

<div class="card mt-4">
  <h3 class="card-header fw-semibold"> <span class="mx-3"><?= htmlspecialchars($pageTitleAsistencia); ?></span></h3>
  <div class="card-body">
    <div class="mt-4">
      <table id="tablaResumen" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <tr>
            <th>Estudiante</th>
            <th>Hora Entrada</th>
            <th>Hora Salida</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <!-- Los datos se cargarán dinámicamente -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

$pageScripts = '
<script src="js/resumen_asistencias_clase.js"></script>
<script>
    $(document).ready(function() {
        initResumenAsistenciasClase();
    });
</script>
';

require_once __DIR__ . '/../admin/layouts/main.php';
?>