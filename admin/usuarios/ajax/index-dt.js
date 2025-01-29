jQuery(function ($) {
  var table = initializeDataTable("#dt", "php/datos-dt.php", languageConfig);
  disableButtons(table, [2, 3, 4]);
  toggleRowSelection(table, "#dt tbody", [2, 3, 4]);
})

function crear() {
  Swal.fire({
    title: '<i class="fas fa-user-plus"></i>Crear Usuario',
    customClass: {
      container: 'custom-modal'
    },
    html: `
      <div class="input-icon-wrapper">
        <i class="input-icon fa-solid fa-user"></i>
        <input type="text" id="username" class="swal2-input" placeholder="&nbsp;&nbsp; Nombre de Usuario">
      </div>

      <div class="input-icon-wrapper">
        <i class="input-icon fa-solid fa-lock"></i>
        <input type="password" id="password" class="swal2-input" placeholder="&nbsp;&nbsp; Contraseña" value="">
      </div>

      <div class="input-icon-wrapper">
        <i class="input-icon fa-solid fa-id-card"></i>
        <input type="text" id="apellidos" class="swal2-input" placeholder="&nbsp;&nbsp;&nbsp; Apellidos">
      </div>

      <div class="input-icon-wrapper">
        <i class="input-icon fa-solid fa-id-card"></i>
        <input type="text" id="nombres" class="swal2-input " placeholder="&nbsp;&nbsp;&nbsp; Nombres" >
      </div>

      <select id="role" class="swal2-select">
        <option value="admin">Admin</option>
        <option value="docente" >Docente</option>
        <option value="estudiante" selected>Estudiante</option>
      </select>

      
      <select id="status" class="swal2-select">
        <option value="activo" selected >Activo</option>
        <option value="inactivo" >Inactivo</option>
      </select>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: '<i class="fa-sharp fa-solid fa-floppy-disk"></i> Guardar',
    cancelButtonText: '<i class="fa-solid fa-xmark"></i> Cancelar',
    // width: '32em',
    didOpen: () => {
      const inputs = ['username', 'password', 'apellidos', 'nombres'];
      inputs.forEach(id => {
        const input = document.getElementById(id);
        const icon = input.previousElementSibling;

        input.addEventListener('input', function () {
          icon.style.opacity = this.value ? '0' : '1';
        });
      });
    },
    preConfirm: () => {
      const username = Swal.getPopup().querySelector("#username").value;
      const password = Swal.getPopup().querySelector("#password").value;
      const apellidos = Swal.getPopup().querySelector("#apellidos").value;
      const nombres = Swal.getPopup().querySelector("#nombres").value;
      const role = Swal.getPopup().querySelector("#role").value;
      const status = Swal.getPopup().querySelector("#status").value;

      if (!username || !apellidos || !nombres) {
        Swal.showValidationMessage("Por favor, complete todos los campos obligatorios");
        return false;
      }

      return { id: null, username, password, apellidos, nombres, role, status };
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const url = "php/crear.php";
      fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(result.value)
      })
        .then(response => handleResponse(response, "¡Creado!", "Usuario creado", true));

    } else {
      $('#dt').DataTable().ajax.reload(null, false);
    }
  })
}

function crearMasivo() {
  Swal.fire({
    title: '<i class="fas fa-users-plus"></i> Crear Usuarios Masivamente',
    html: `
      <p>Sube un archivo CSV con los datos de los usuarios.</p>
      <p><a href="descargar_plantilla_csv.php" target="_blank">Descargar plantilla CSV</a></p>
      <input type="file" id="csvFile" accept=".csv" class="swal2-file">
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: '<i class="fa-sharp fa-solid fa-upload"></i> Cargar',
    cancelButtonText: '<i class="fa-solid fa-xmark"></i> Cancelar',
    preConfirm: () => {
      const file = Swal.getPopup().querySelector('#csvFile').files[0];
      if (!file) {
        Swal.showValidationMessage("Por favor, selecciona un archivo CSV");
        return false;
      }
      return file;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const file = result.value;
      const formData = new FormData();
      formData.append('csv_file', file);

      fetch("php/crear_masivo.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('¡Éxito!', data.message, 'success');
          $('#dt').DataTable().ajax.reload(null, false);
        } else {
          console.error('Error details:', data);
          Swal.fire('Error', `${data.message}\n\nPor favor, revisa la consola para más detalles.`, 'error');
        }
      })
      .catch(error => {
        console.error('Fetch error:', error);
        Swal.fire('Error', 'Hubo un problema al procesar la solicitud. Por favor, revisa la consola para más detalles.', 'error');
      });
    }
  });
}

function editar(id) {
  // Realiza la solicitud para obtener los datos del usuario
  fetch("php/editar.php?id=" + id)
    .then(response => response.json())
    .then(data => {
      // Mostrar el formulario con los datos actuales del usuario
      Swal.fire({
        title: '<i class="fa-solid fa-user-pen"></i> Editar Usuario',
        customClass: {
          container: 'custom-modal'
        },
        html: `
            <input type="text" id="username" class="swal2-input" value="${data.username}" placeholder="Nombre de Usuario">
            
            <input type="text" id="apellidos" class="swal2-input" value="${data.apellidos}" placeholder="Apellidos">
            <input type="text" id="nombres" class="swal2-input" value="${data.nombres}" placeholder="Nombres">
            <select id="role" class="swal2-select">
                <option value="admin" ${data.role === "admin" ? "selected" : ""}>Admin</option>
                <option value="docente" ${data.role === "docente" ? "selected" : ""}>Docente</option>
                <option value="estudiante" ${data.role === "estudiante" ? "selected" : ""}>Estudiante</option>
            </select>
            <select id="status" class="swal2-select">
                <option value="activo" ${data.status === "activo" ? "selected" : ""}>Activo</option>
                <option value="inactivo" ${data.status === "inactivo" ? "selected" : ""}>Inactivo</option>
            </select>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Actualizar",
        cancelButtonText: "Cancelar",
        preConfirm: () => {
          const username = Swal.getPopup().querySelector("#username").value;

          const apellidos = Swal.getPopup().querySelector("#apellidos").value;
          const nombres = Swal.getPopup().querySelector("#nombres").value;
          const role = Swal.getPopup().querySelector("#role").value;
          const status = Swal.getPopup().querySelector("#status").value;

          if (!username || !apellidos || !nombres) {
            Swal.showValidationMessage("Por favor, complete todos los campos");
            return false;
          }

          return { id, username, apellidos, nombres, role, status };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Enviar los datos actualizados al servidor
          fetch("php/actualizar.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify(result.value)
          }).then(response => handleResponse(response, "¡Editado!", "El usuario ha sido actualizado", false))
            .then(() => {
              $('#dt').DataTable().ajax.reload(null, false); // Actualiza la tabla tras la respuesta
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.close();
        }
      });
    })
    .catch(error => {
      Swal.fire("Error", "No se pudo obtener los datos del usuario", "error");
      console.error("Error:", error);
    });
}

function eliminar(id) {
  // Mostrar la confirmación para eliminar el usuario
  Swal.fire({
    title: "¿Estás seguro?",
    text: "No podrás deshacer esta acción.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      // Enviar la solicitud al servidor para eliminar el usuario
      fetch("php/eliminar.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: id })
      }).then(response => handleResponse(response, "¡Eliminado!", "El usuario ha sido eliminado", false))
        .then(() => {
          $('#dt').DataTable().ajax.reload(null, false); // Actualiza la tabla tras la respuesta
        });

    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.close();
    }
  });
}

function ver(id) {
  // Realiza la solicitud para obtener los datos del usuario
  fetch("php/ver.php?id=" + id)
    .then(response => response.json())
    .then(data => {
      // Mostrar los detalles del usuario en un SweetAlert
      Swal.fire({
        title: "Detalles del Usuario",
        html: `
          <strong>Nombre de Usuario:</strong> ${data.username}<br>
          <strong>Apellidos:</strong> ${data.apellidos}<br>
          <strong>Nombres:</strong> ${data.nombres}<br>
          <strong>Género:</strong> ${data.genero ? data.genero : 'No especificado'}<br>
          <strong>DNI:</strong> ${data.dni}<br>
          <strong>Email:</strong> ${data.email}<br>
          <strong>Rol:</strong> ${ucfirst(data.role)}<br>
          <strong>Estado:</strong> ${data.status === "activo" ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'}<br>
          <strong>Foto:</strong> <br>
          <img src="../../uploads/${data.role}/${data.foto ? data.foto : 'default.png'}" alt="Foto del Usuario" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid; border-color:#D1D1D1 ;border-radius: 50%;">
        `,
        confirmButtonText: "Cerrar",
        focusConfirm: false
      });
    })
    .catch(error => {
      Swal.fire("Error", "No se pudo obtener los detalles del usuario", "error");
      console.error("Error:", error);
    });
}

// Función auxiliar para capitalizar la primera letra de un string
function ucfirst(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function handleResponse(response, titleMessage, successMessage, continuo = false) {
  response.json().then(data => {
    if (data.success) {
      Swal.fire(titleMessage, successMessage, 'success').then(() => {
        if (continuo) {
          crear();  // Llamar a crear() si el flujo es continuo
        }
      });
    } else {
      Swal.fire('Error', data.message, 'error');
    }
  }).catch(error => {
    Swal.fire('Error', 'Hubo un problema al procesar la solicitud', 'error');
    console.error('Error:', error);
  });
}