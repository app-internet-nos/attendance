<?php
require_once __DIR__ . '/../config/config.php';

if (!isAuthenticated() || !hasRole('admin')) {
  redirect(LOGIN_URL);
  exit();
}

$pageTitle = "Reporte de Asistencia por Clase";
$pageTitleAsistencia = "Asistencias";

$conn = conectarDB();
// Obtener lista de clases para el menú desplegable
$consultaClases = "SELECT c.id, a.nombre AS asignatura, CONCAT(d.apellidos, ', ', d.nombres) AS docente, 
                 CONCAT(pe.nombre_corto, ' - ', ci.nombre, ' - ', s.año) AS seccion
          FROM clases c
          JOIN asignaturas a ON c.id_asignatura = a.id
          JOIN usuarios d ON c.id_docente = d.id
          JOIN secciones s ON c.id_seccion = s.id
          JOIN programas_estudio pe ON s.id_programa_estudio = pe.id
          JOIN ciclos ci ON s.id_ciclo = ci.id
          ORDER BY pe.nombre_corto, ci.nombre, a.nombre";

$resultadoClases = $conn->query($consultaClases);
$clases = $resultadoClases->fetch_all(MYSQLI_ASSOC);

$conn->close();

ob_start();
?>


<div class="card">
  <h3 class="card-header fw-semibold"> <span class="mx-3"><?= htmlspecialchars($pageTitle); ?></span></h3>
  <div class="card-body mx-3">

    <form id="formularioReporte" novalidate >
      <div class="row">
        <div class="col-12 col-md-12 col-lg-6">
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

        <div class="col-12 col-md-6 col-lg-3">
          <div class="form-group">
            <label for="fecha_inicio" class="mb-2 fw-semibold">Fecha de Inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
          </div>


        </div>
        <div class="col-12 col-md-6 col-lg-3">
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
<script src="js/asistencias_por_clase.js"></script>
<script>
    $(document).ready(function() {
        initAsistenciasPorClase();
    });
</script>
';


require_once __DIR__ . '/../admin/layouts/main.php';
?>