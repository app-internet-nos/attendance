<?php
require_once __DIR__ . '/../../../config/config.php';


// Verificar autenticación y rol de administrador
if (!isAuthenticated() || $_SESSION['role'] !== ROLE_ADMIN) {
  header("Location: " . LOGIN_URL);
  exit();
}

$conn = conectarDB();

// Consultas de estadísticas
$queries = [
  'admin' => "SELECT COUNT(*) FROM usuarios WHERE role = 'admin'",
  'docentes' => "SELECT COUNT(*) FROM usuarios WHERE role = 'docente'",
  'estudiantes' => "SELECT COUNT(*) FROM usuarios WHERE role = 'estudiante'",
  'usuarios_activos' => "SELECT COUNT(*) FROM usuarios WHERE status = 'activo'",
  'usuarios_inactivos' => "SELECT COUNT(*) FROM usuarios WHERE status = 'inactivo'",
  'usuarios_por_rol' => "SELECT role, COUNT(*) as total FROM usuarios GROUP BY role",
  'usuarios_recientes' => "SELECT COUNT(*) FROM usuarios WHERE fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)",
  'ultima_conexion' => "SELECT MAX(ultima_conexion) as ultima FROM usuarios"
];

$stats = [];
$errors = [];

foreach ($queries as $key => $query) {
  try {
    $result = $conn->query($query);
    if ($key === 'usuarios_por_rol') {
      $stats[$key] = $result->fetch_all(MYSQLI_ASSOC);
    } elseif ($key === 'ultima_conexion') {
      $stats[$key] = $result->fetch_assoc()['ultima'];
    } else {
      $stats[$key] = $result->fetch_row()[0];
    }
  } catch (mysqli_sql_exception $e) {
    $errors[$key] = "Error en la consulta de $key: " . $e->getMessage();
  }
}

$conn->close();

// Título de la página
$pageTitle = "Visión general de usuarios";
ob_start();
?>

<div class="container-fluid py-4">

  <h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="row">
    <?php
    $cardInfo = [
      ['title' => 'Administradores', 'count' => $stats['admin'], 'icon' => 'user-shield', 'bg' => 'primary'],
      ['title' => 'Docentes', 'count' => $stats['docentes'], 'icon' => 'chalkboard-teacher', 'bg' => 'success'],
      ['title' => 'Estudiantes', 'count' => $stats['estudiantes'], 'icon' => 'user-graduate', 'bg' => 'info'],
      ['title' => 'Total Usuarios', 'count' => $stats['usuarios_activos'] + $stats['usuarios_inactivos'], 'icon' => 'users', 'bg' => 'warning', 'extra' => [
        'Activos' => $stats['usuarios_activos'],
        'Inactivos' => $stats['usuarios_inactivos']
      ]]
    ];

    foreach ($cardInfo as $card) : ?>
      <div class="col-md-3 mb-4">
        <div class="card bg-<?php echo $card['bg']; ?> text-white h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-<?php echo $card['icon']; ?> me-2"></i><?php echo $card['title']; ?></h5>
            <p class="card-text display-4"><?php echo $card['count']; ?></p>
          </div>
          <?php if (isset($card['extra'])) : ?>
            <div class="card-footer bg-transparent border-0">
              <span class="text-success me-2 fw-bolder"><i class="fas fa-check-circle"></i> <?php echo $card['extra']['Activos']; ?> activos</span>
              <span class="text-danger fw-bolder"><i class="fas fa-times-circle"></i> <?php echo $card['extra']['Inactivos']; ?> inactivos</span>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row">
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Distribución de Usuarios por Rol</h5>
        </div>
        <div class="card-body">
          <canvas id="usuarios-rol-chart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Estadísticas Adicionales</h5>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Usuarios nuevos (últimos 30 días)
              <span class="badge bg-primary rounded-pill"><?php echo $stats['usuarios_recientes']; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Última conexión de usuario
              <span class="badge bg-info rounded-pill"><?php echo $stats['ultima_conexion'] ? date('d/m/Y H:i', strtotime($stats['ultima_conexion'])) : 'N/A'; ?></span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

$labels = json_encode(array_column($stats["usuarios_por_rol"], "role"), JSON_UNESCAPED_UNICODE);
$data = json_encode(array_column($stats["usuarios_por_rol"], "total"), JSON_UNESCAPED_UNICODE);

$pageScripts = <<<SCRIPT
<script src="../../../vendor/chart/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var ctxUsuarios = document.getElementById("usuarios-rol-chart").getContext("2d");
    new Chart(ctxUsuarios, {
      type: "pie",
      data: {
        labels: $labels,
        datasets: [{
          data: $data,
          backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: "bottom",
          }
        }
      }
    });
  });
</script>
SCRIPT;

require_once __DIR__ . '/../../../admin/layouts/main.php';
?>