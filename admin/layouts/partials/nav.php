<nav class="navbar navbar-expand-lg navbar-dark bg-primary py-1">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>admin/dashboard"><img src="<?= getImgUrl('logo.png') ?>"
        style="width: 40px; height: 40px;" alt="logo"> SCA-Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

        <!-- Mernu Dashboard -->
        <li class="nav-item">
          <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/dashboard/') !== false)
                                ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/dashboard">Dashboard</a>
        </li>



        <!-- Menú académicos -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/usuarios/index.php') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/admin/programas_estudio/') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/admin/asignaturas/') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/admin/ciclos/') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/admin/secciones/') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/admin/clases/') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/admin/asignaciones/') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/admin/estudiantes_clases/') !== false)
                                                ? 'active' : ''; ?>" href="#" id="academicosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Académicos
          </a>
          <ul class="dropdown-menu" aria-labelledby="academicosDropdown">
            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/usuarios/index.php') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/usuarios/">Usuarios</a></li>
            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/programas_estudio/') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/programas_estudio/">Programas estudio</a></li>

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/asignaturas/') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/asignaturas/">Unidades didácticas</a></li>

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/ciclos/') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/ciclos/">Ciclos</a></li>

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/secciones/') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/secciones/">Secciones</a></li>

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/clases/') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/clases/">Clases</a></li>

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/estudiantes_clases/') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/estudiantes_clases/">Asignaciones</a></li>
          </ul>
        </li>

        <!-- Menú Reporte  -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/usuarios/php/vista_general.php') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/reportes/asistencia_por_clase.php') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/reportes/asistencia_por_estudiante.php') !== false ||
                                                strpos($_SERVER['PHP_SELF'], '/reportes/resumen_asistencias_clase.php') !== false)
                                                ? 'active' : ''; ?>" href="#" id="reportesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Reportes
          </a>
          <ul class="dropdown-menu" aria-labelledby="academicosDropdown">
            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/usuarios/php/vista_general.php') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/usuarios/php/vista_general.php">Vision general de usuarios</a></li>

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/reportes/asistencia_por_clase.php') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>reportes/asistencia_por_clase.php">Asistencias por Clase</a></li>

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/reportes/asistencia_por_estudiante.php') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>reportes/asistencia_por_estudiante.php">Asistencias por estudiante</a></li>

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/reportes/asistencia_diaria.php') !== false)
                                          ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>reportes/resumen_asistencias_clase.php">Asistencias diaria</a></li>
          </ul>
        </li>
      </ul>

      <!-- Nenú perfil logout  -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">




          <a class="nav-link dropdown-toggle <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/perfil/index.php') !== false)
                                                ? 'active' : ''; ?>"
            href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">


            <img src="<?= getUploadUrl($_SESSION['userFoto']) ?>"
              alt="Foto del Usuario" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid; border-color:#D1D1D1 ;border-radius: 50%;">
            <?= $_SESSION['username']; ?>
          </a>


          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

            <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/perfil/index.php') !== false)
                                          ? 'active' : ''; ?>" href="/admin/perfil/index.php">Mi Perfil</a></li>
            <li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="/admin/auth/logout.php">Cerrar Sesión</a></li>
          </ul>
        </li>
      </ul>

    </div>
  </div>
</nav>