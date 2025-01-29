function initAsistenciasPorEstudiante() {
  let dataTable;
  const spanishLanguage = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",

    "oAria": {
      "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "buttons": {
      "copy": "Copiar",
      "colvis": "Visibilidad"
    }
  };

  $('#estudiante_id').change(function () {
    const estudianteId = $(this).val();
    const claseSelect = $('#clase_id');

    claseSelect.prop('disabled', true).html('<option value="">Cargando clases...</option>');

    if (estudianteId) {
      $.ajax({
        url: 'obtener_clases_estudiante.php',
        type: 'POST',
        data: { estudiante_id: estudianteId },
        dataType: 'json',
        success: function (clases) {
          claseSelect.html('<option value="">Seleccione una clase</option>');
          clases.forEach(function (clase) {
            claseSelect.append(`<option value="${clase.id}">${clase.asignatura} - ${clase.docente}</option>`);
          });
          claseSelect.prop('disabled', false);
        },
        error: function () {
          toastr.error('Error al cargar las clases del estudiante');
          claseSelect.html('<option value="">Error al cargar clases</option>');
        }
      });
    } else {
      claseSelect.html('<option value="">Primero seleccione un estudiante</option>');
    }
  });

  $('#formularioReporte').on('submit', function (e) {
    e.preventDefault();

    if (dataTable) {
      dataTable.destroy();
    }

    $.ajax({
      url: 'obtener_asistencias_estudiante.php',
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

        dataTable = $('#tablaAsistencia').DataTable({
          data: response.data,
          columns: response.columns,
          language: spanishLanguage,
          responsive: true,
          scrollX: true,
          columnDefs: [
            {
              targets: '_all',
              defaultContent: 'E:-- S:--'
            }
          ]
        });

        $('#tablaAsistencia').show();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        toastr.error('Error al obtener los datos: ' + textStatus + ' ' + errorThrown);
      }
    });
  });
}