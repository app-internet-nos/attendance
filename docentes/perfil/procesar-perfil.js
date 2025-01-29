document.addEventListener('DOMContentLoaded', function() {
  const formPass = document.getElementById('formPass');
  const passwordTabPane = document.getElementById('password-tab-pane');
  const formPerfil = document.getElementById('formPerfil');
  const updateProfileBtn = document.getElementById('updateProfileBtn');

  // Función para manejar cambios en el formulario de perfil
  function handleProfileFormChange() {
    updateProfileBtn.disabled = false;
  }

  // Agregar event listeners a todos los campos del formulario de perfil
  const perfilInputs = formPerfil.querySelectorAll('input, select');
  perfilInputs.forEach(input => {
    input.addEventListener('change', handleProfileFormChange);
    if (input.type === 'text' || input.type === 'email') {
      input.addEventListener('input', handleProfileFormChange);
    }
  });

  formPass.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(formPass);
    formData.append('change_password', '1');

    fetch('procesar_perfil.php', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        toastr.success(data.message);
        reloadPasswordTab();
      } else {
        toastr.error(data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      toastr.error('Ha ocurrido un error. Por favor, intente nuevamente.');
    });
  });

  function reloadPasswordTab() {
    fetch('index.php #password-tab-pane', {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.text())
    .then(html => {
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = html;
      const newTabContent = tempDiv.querySelector('#password-tab-pane');
      if (newTabContent) {
        passwordTabPane.innerHTML = newTabContent.innerHTML;
        const newFormPass = passwordTabPane.querySelector('#formPass');
        if (newFormPass) {
          newFormPass.addEventListener('submit', formPass.onsubmit);
        }
      }
    })
    .catch(error => {
      console.error('Error al recargar la pestaña:', error);
      toastr.error('Error al recargar la pestaña. Por favor, actualice la página.');
    });
  }

  // Código existente para la vista previa de la imagen
  document.getElementById("foto").addEventListener("change", function(event) {
    const input = event.target;

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function(e) {
        document.getElementById("img-preview").src = e.target.result;
      }

      reader.readAsDataURL(input.files[0]);
    }
    // Activar el botón cuando se cambia la foto
    handleProfileFormChange();
  });
});

document.getElementById("foto").addEventListener("change", function(event) {
  const input = event.target;

  // Verificar si hay un archivo seleccionado
  if (input.files && input.files[0]) {
    const reader = new FileReader();

    // Función que se ejecuta cuando se ha leído la imagen
    reader.onload = function(e) {
      // Actualizar la src de la imagen con la nueva URL de la imagen cargada
      document.getElementById("img-preview").src = e.target.result;
    }

    // Leer el archivo seleccionado como una URL de datos
    reader.readAsDataURL(input.files[0]);
  }
});