<?php
require_once __DIR__ . '/../config/config.php';

if (!isAuthenticated() || !hasRole('admin')) {
  redirect(LOGIN_URL);
  exit();
}

$pageTitle = "Reporte de Asistencia por Estudiante";
$pageTitleAsistencia = "Asistencias";

$conn = conectarDB();
// Obtener lista de estudiantes para el primer select
$consultaEstudiantes = "SELECT u.id, u.apellidos, u.nombres, CONCAT(u.apellidos, ', ', u.nombres) AS nombre_completo
                        FROM usuarios u
                        JOIN estudiantes_clases ec ON u.id = ec.id_estudiante
                        WHERE u.role = 'estudiante'
                        GROUP BY u.id, u.apellidos, u.nombres
                        ORDER BY u.apellidos, u.nombres";

$resultadoEstudiantes = $conn->query($consultaEstudiantes);

if (!$resultadoEstudiantes) {
  die("Error en la consulta: " . $conn->error);
}

$estudiantes = $resultadoEstudiantes->fetch_all(MYSQLI_ASSOC);

$conn->close();

ob_start();
?>

<div class="card">
  <h3 class="card-header fw-semibold"> <span class="mx-3"><?= htmlspecialchars($pageTitle); ?></span></h3>
  <div class="card-body mx-3">
    <form id="formularioReporte" novalidate>
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="estudiante_id" class="mb-2 fw-semibold">Seleccionar Estudiante:</label>
            <select class="form-select" id="estudiante_id" name="estudiante_id" required>
              <option value="">Seleccione un estudiante</option>
              <?php foreach ($estudiantes as $estudiante): ?>
                <option value="<?= $estudiante['id']; ?>">
                  <?= htmlspecialchars($estudiante['nombre_completo']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-12 mt-3 col-md-6 mt-md-0 ">
          <div class="form-group">
            <label for="clase_id" class="mb-2 fw-semibold">Seleccionar Clase:</label>
            <select class="form-select" id="clase_id" name="clase_id" required disabled>
              <option value="">Primero seleccione un estudiante</option>
            </select>
          </div>
        </div>
        <div class="col-12 mt-3 col-md-6  mt-md-3">
          <div class="form-group">
            <label for="fecha_inicio" class="mb-2 fw-semibold">Fecha de Inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
          </div>
        </div>
        <div class="col-12 mt-3 col-md-6 mt-md-3">
          <div class="form-group">
            <label for="fecha_fin" class="mb-2 fw-semibold">Fecha de Fin:</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer text-center">
    <span class="mx-3"><button type="submit" form="formularioReporte" class="btn btn-primary">Generar Reporte</button></span>
  </div>
</div>

<div class="card mt-4">
  <h3 class="card-header fw-semibold"> <span class="mx-3"><?= htmlspecialchars($pageTitleAsistencia); ?></span></h3>
  <div class="card-body">
    <div class="mt-4">
      <table id="tablaAsistencia" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <!-- Las columnas se generarán dinámicamente -->
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
<script src="js/asistencias_por_estudiante.js"></script>
<script>
    $(document).ready(function() {
        initAsistenciasPorEstudiante();
    });
</script>
';

require_once __DIR__ . '/../admin/layouts/main.php';
?>