<?php
require_once 'procesar_perfil.php';
$pageTitle = "Perfil del Admin";
ob_start();
?>
<div class="container">

  <ul class="nav nav-tabs" id="perfilAdminTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="perfil-tab" data-bs-toggle="tab" data-bs-target="#perfil-tab-pane" type="button" role="tab" aria-controls="perfil-tab-pane" aria-selected="true">Perfil del docente</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab" aria-controls="password-tab-pane" aria-selected="false">Cambiar contraseña</button>
    </li>

  </ul>

  <div class="tab-content" id="perfilAdminTabContent">
    <div class="tab-pane fade show active" id="perfil-tab-pane" role="tabpanel" aria-labelledby="perfil-tab" tabindex="0">
      <div class="card border-top-0">

        <div class="car-body pt-4 pe-4 pb-4 ps-4 border-0">
          <form id="formPerfil" method="POST" enctype="multipart/form-data">
            <div class="row mb-2">
              <div class="col-12 col-md-6 mb-3">
                <label for="nombres" class="form-label mb-1">Nombres</label>
                <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo htmlspecialchars($user['nombres']); ?>" required>
              </div>
              <div class="col-12 col-md-6 mb-3">
                <label for="apellidos" class="form-label mb-1">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($user['apellidos']); ?>" required>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-12 col-md-4 mb-3">
                <label for="email" class="form-label mb-1">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
              </div>
              <div class="col-12 col-md-4 mb-3">
                <label for="dni" class="form-label mb-1">DNI</label>
                <input type="text" class="form-control" id="dni" value="<?php echo htmlspecialchars($user['dni']); ?>" disabled>
              </div>
              <div class="col-12 col-md-4 mb-3">
                <label for="genero" class="form-label mb-1">Género</label>
                <select class="form-select" id="genero" name="genero">
                  <option value="Masculino" <?php echo $user['genero'] === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                  <option value="Femenino" <?php echo $user['genero'] === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                </select>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <label for="foto" class="form-label me-3">Foto de perfil</label>
              <?php if ($user['foto']): ?>
                <img id="img-preview" src="<?php echo getUploadUrl('admin/' . $user['foto']); ?>" alt="Foto de perfil" class="img-thumbnail rounded-pill mb-2" style="max-width: 125px;">
              <?php else: ?>
                <img id="img-preview" src="<?php echo getUploadUrl('admin/default.png'); ?>" alt="Foto de perfil" class="img-thumbnail rounded-pill mb-2" style="max-width: 125px;">
              <?php endif; ?>
              <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
            </div>


          </form>
        </div>
        <div class="card-footer text-center border-top-0 bg-transparent mb-4">
          <button type="submit" form="formPerfil" name="update_profile" id="updateProfileBtn" class="btn btn-primary" disabled>Actualizar Perfil</button>
        </div>
      </div>


    </div>

    <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab" tabindex="0">

      <div class="card border-top-0">

        <div class="card-body car-body pt-4 pe-4 ps-4">
          <form id="formPass" method="POST" novalidate>
            <div class="row">
              <div class="col-12 col-md-4">
                <div class="mb-2">
                  <label for="current_password" class="form-label">Contraseña Actual</label>
                  <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="mb-2">
                  <label for="new_password" class="form-label">Nueva Contraseña</label>
                  <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="mb-2">
                  <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer text-center bg-transparent border-top-0 mb-4">
          <button type="submit" form="formPass" name="change_password" class="btn btn-secondary">Cambiar Contraseña</button>
        </div>
      </div>
    </div>
  </div>

</div>

<?php
$content = ob_get_clean();

$pageStyles = '<link rel="stylesheet" href="/docentes/css/styles.css">';
$pageScripts = 
'
  <script> ' . $toastrScript . ' </script>;
  <script src="procesar-perfil.js"></script>
';

include __DIR__ . '/../layouts/main.php';
?>
