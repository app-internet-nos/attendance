<?php
session_start();
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../config/config.php';

// Verificar si hay un mensaje en la sesión
if (isset($_SESSION['mensaje'])) {
  $mensaje = $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
}

// Obtener estadísticas
$conn = conectarDB();
$user_id = $_SESSION['user_id'];

$total_estudiantes = $conn->query("SELECT COUNT(*) FROM usuarios WHERE role = 'estudiante'")->fetch_row()[0];
$total_docentes = $conn->query("SELECT COUNT(*) FROM usuarios WHERE role='docente'")->fetch_row()[0];
$total_admins = $conn->query("SELECT COUNT(*) FROM usuarios WHERE role='admin'")->fetch_row()[0];

$total_clases = $conn->query("SELECT COUNT(*) FROM clases")->fetch_row()[0];
$asistencias_hoy = $conn->query("SELECT COUNT(*) FROM marcados WHERE DATE(fecha_hora) = CURDATE()")->fetch_row()[0];

// Estadísticas adicionales
$total_programas = $conn->query("SELECT COUNT(*) FROM programas_estudio")->fetch_row()[0];
$total_secciones = $conn->query("SELECT COUNT(*) FROM secciones")->fetch_row()[0];

$conn->close();

// Inicio del contenido
ob_start();
?>

<?php if (isset($mensaje)): ?>
  <div class="alert alert-success"><?php echo $mensaje; ?></div>
<?php endif; ?>

<div class="container-fluid mt-4">
  <h1 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Administrativo</h1>

  <div class="row">
    <div class="col-md-3 mb-4">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-user-graduate me-2"></i>Total Estudiantes</h5>
          <p class="card-text display-4"><?php echo $total_estudiantes; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i>Total Docentes</h5>
          <p class="card-text display-4"><?php echo $total_docentes; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card bg-info text-white">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-user-shield me-2"></i>Total Administradores</h5>
          <p class="card-text display-4"><?php echo $total_admins; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card bg-warning text-dark">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Asistencias Hoy</h5>
          <p class="card-text display-4"><?php echo $asistencias_hoy; ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-clock me-2"></i>Últimas Asistencias Registradas
        </div>
        <div class="card-body">
          <ul class="list-group">

        </ul>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-chart-bar me-2"></i>Clases con Más Asistencias Hoy
        </div>
        <div class="card-body">
          <ul class="list-group">

          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-bolt me-2"></i>Acciones Rápidas
        </div>
        <div class="card-body">
          <div class="list-group">
            <a href="../usuarios/" class="list-group-item list-group-item-action">
              <i class="fas fa-user-plus me-2"></i>Registrar nuevo usuario
            </a>
            <a href="../asignaturas/" class="list-group-item list-group-item-action">
              <i class="fas fa-chalkboard-teacher me-2"></i>Registrar nueva UD
            </a>
            <a href="../clases/" class="list-group-item list-group-item-action">
              <i class="fa-solid fa-screen-users"></i> Crear nueva Clase
            </a>
            <a href="../estudiantes_clases/" class="list-group-item list-group-item-action">
              <i class="fa-sharp fa-solid fa-person-chalkboard"></i> Asignar estudiantes a UD
            </a>
            <a href="#" class="list-group-item list-group-item-action">
              <i class="fas fa-file-alt me-2"></i>Ver Reporte de Asistencia Diaria
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-chart-pie me-2"></i>Estadísticas Adicionales
        </div>
        <div class="card-body">
          <p><i class="fas fa-graduation-cap me-2"></i>Total de Programas de Estudio: <strong><?php echo $total_programas; ?></strong></p>
          <p><i class="fas fa-users me-2"></i>Total de Secciones: <strong><?php echo $total_secciones; ?></strong></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

// Incluir el layout principal
$pageTitle = "Dashboard Administrativo";
require_once '../layouts/main.php';
?>