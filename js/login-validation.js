// Archivo: js/login-validation.js

jQuery(function ($) {
  // Configuración de toastr

  configureToastr({
    positionClass: "toast-top-center", // Ejemplo de personalización
    timeOut: "2000"
  });


  // Validación del formulario
  $('#loginForm').on('submit', function (e) {
    e.preventDefault();

    var username = $('#username').val().trim();
    var password = $('#password').val().trim();
    var isValid = true;

    // Limpiar mensajes de error anteriores
    toastr.clear();

    // Validar nombre de usuario

    if (username === '' && password === '') {
      showToastrMessage('error', 'Los campos nombre de usuario y password no pueden estar vacios');
      isValid = false
    } else if (username === '' && password !== '') {
      showToastrMessage('error', 'El campo Nombre de usuario no puede estar vacío.');
      isValid = false;
    } else if (username !== '' && password === '') {
      showToastrMessage('error', 'El campo password  no puede estar vacío.');
      isValid = false;
    };

    // if (username === '') {
    //   showToastrMessage('error', 'El campo Nombre de usuario no puede estar vacío.');
    //   isValid = false;
    // }

    // // Validar contraseña
    // if (password === '') {
    //   showToastrMessage('error', 'El campo Contraseña no puede estar vacío.');
    //   isValid = false;
    // }

    if (isValid) {
      // Si todo es válido, enviar el formulario mediante AJAX
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
          if (response.success) {

            window.location.href = response.redirect;
            // showToastrMessage('success', response.message);
            // setTimeout(function () {
            //   window.location.href = response.redirect;

            // }, 1500);

          } else {
            showToastrMessage('error', response.message);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          showToastrMessage('error', 'Error en la solicitud: ' + textStatus + ' - ' + errorThrown);
        }
      });
    }
  });
});