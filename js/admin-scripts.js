// Configuración global de Toastr
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-center",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "2000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

// Función para mostrar mensajes Toastr
function showToastr(message, type = 'success') {
  toastr[type](message);
}

// Validación de DNI
jQuery(function ($) {
  $('#dni').on('input', function () {
    var inputVal = $(this).val();
    if (inputVal.length == 8 && !isNaN(inputVal)) {
      $(this).removeClass('is-invalid').addClass('is-valid');
    } else {
      $(this).removeClass('is-valid').addClass('is-invalid');
    }
  });
});