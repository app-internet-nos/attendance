function initResumenAsistenciasClase() {
  let dataTable;
  const spanishLanguage = {
    // ... (mantener el objeto de idioma español como antes)
  };

  $('#formularioResumen').on('submit', function (e) {
    e.preventDefault();

    if (dataTable) {
      dataTable.destroy();
    }

    $.ajax({
      url: 'obtener_resumen_asistencias.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        if (response.error) {
          toastr[response.type](response.error);
          return;
        }

        if (response.message) {
          toastr[response.type](response.message);
        }

        dataTable = $('#tablaResumen').DataTable({
          data: response.data,
          columns: [
            { data: 'estudiante' },
            { data: 'hora_entrada' },
            { data: 'hora_salida' },
            { data: 'estado' }
          ],
          language: spanishLanguage,
          responsive: true,
          order: [[0, 'asc']]
        });

        $('#tablaResumen').show();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        toastr.error('Error al obtener los datos: ' + textStatus + ' ' + errorThrown);
      }
    });
  });
}