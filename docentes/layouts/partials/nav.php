<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">

    <a class="navbar-brand" href="<?php echo BASE_URL; ?>docentes/dashboard"><img src="<?= getImgUrl('logo.png') ?>"
        style="width: 40px; height: 40px;" alt="logo"> SCA-Docente</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <!-- Mernu Dashboard -->
        <li class="nav-item">
          <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/docentes/dashboard/') !== false)
                                ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>docentes/dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/docentes/perfil/test.php') !== false)
                                ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>docentes/perfil/test.php">Test</a>
        </li>
      </ul>


        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle <?php echo (strpos($_SERVER['PHP_SELF'], '/docentes/perfil/index.php') !== false)
                                                  ? 'active' : ''; ?>"
              href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

              <img src="<?= getUploadUrl($_SESSION['userFoto']) ?>"
                alt="Foto del Usuario" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid; border-color:#D1D1D1 ;border-radius: 50%;">
              <?= $_SESSION['username']; ?>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item <?php echo (strpos($_SERVER['PHP_SELF'], '/docentes/perfil/index.php') !== false)
                                            ? 'active' : ''; ?>" href="/docentes/perfil/index.php">Mi Perfil</a></li>
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