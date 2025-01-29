function initializeForm() {
  configureToastr({
    positionClass: "toast-top-center",
    timeOut: "1000"
  });

  function handleFormSubmit(formId, validateFunction, url) {
    $(formId).on('submit', function (e) {
      e.preventDefault();
      if (validateFunction()) {
        $.ajax({
          url: url,
          type: 'POST',
          data: $(this).serialize(),
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              showToastrMessage('success', response.message);
              setTimeout(function () {
                cargarFormulario();
              }, 1500);
            } else {
              showToastrMessage('error', response.message);
              setTimeout(function () {
                cargarFormulario();
              }, 1500);
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud:', textStatus, errorThrown);
            showToastrMessage('error', 'Error en la solicitud. Por favor, intente de nuevo.');
          }
        });
      }
    });
  }

  function validateDniForm() {
    var dni = $('#dni').val().trim();
    if (dni === '') {
      showToastrMessage('error', 'El campo DNI no puede estar vacío.');
      return false;
    } else if (!/^\d{8}$/.test(dni)) {
      showToastrMessage('error', 'El DNI debe contener exactamente 8 dígitos.');
      return false;
    }
    return true;
  }

  function validateClaseForm() {
    var clase_id = $('#clase_id').val();
    if (!clase_id) {
      showToastrMessage('error', 'Debe seleccionar una clase.');
      return false;
    }
    return true;
  }

  handleFormSubmit('#loginFormDni', validateDniForm, 'includes/procesar_asistencia.php');
  handleFormSubmit('#loginFormClase', validateClaseForm, 'includes/marcar_asistencia.php');

  $('#dni').on('input', function () {
    var dni = $(this).val().trim();
    if (dni !== '' && !/^\d{0,8}$/.test(dni)) {
      $(this).val(dni.replace(/[^\d]/g, '').slice(0, 8));
    }
  });

  $('#btn-regresar').on('click', function () {
    $.ajax({
      url: '/includes/limpiar_sesion.php',
      method: 'POST',
      dataType: 'json',
      success: function (data) {
        if (data.success) {
          cargarFormulario();
        } else {
          console.error('Error al limpiar la sesión');
        }
      },
      error: function (error) {
        console.error('Error:', error);
      }
    });
  });

  // Nuevas funciones para establecer el foco
  function setFocusOnDni() {
    setTimeout(function () {
      $('#dni').focus();
    }, 100);
  }

  function setFocusOnClaseSelect() {
    setTimeout(function () {
      $('#clase_id').focus();
    }, 100);
  }

  // Determinar qué formulario está presente y establecer el foco adecuado
  if ($('#loginFormDni').length) {
    setFocusOnDni();
  } else if ($('#loginFormClase').length) {
    setFocusOnClaseSelect();
    // Nueva funcionalidad: enviar formulario al presionar Enter en el select
    $('#clase_id').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        $('#loginFormClase').submit();
      }
    });
  }
}

function showToastrMessage(type, message) {
  toastr[type](message);
}

// Asegúrate de que esta función esté disponible globalmente
window.initializeForm = initializeForm;