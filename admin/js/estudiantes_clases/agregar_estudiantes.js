$(document).ready(function () {
  $('#formAgregarEstudiantes').on('submit', function (e) {
    e.preventDefault();
    var estudiantes = $('#estudiantesSelect').val();

    if (estudiantes && estudiantes.length > 0) {
      $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            toastr.success(response.message);
            // Remover los estudiantes agregados de la lista
            estudiantes.forEach(function (id) {
              $('#estudiantesSelect option[value="' + id + '"]').remove();
            });
          } else {
            toastr.error(response.message);
          }
        },
        error: function () {
          toastr.error('Error al agregar los estudiantes');
        }
      });
    } else {
      toastr.warning('Por favor, seleccione al menos un estudiante');
    }
  });
});