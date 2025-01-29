$(document).ready(function () {
  let claseSeleccionadaId = null;
  let estudianteAEliminarId = null;
  let nombreClaseSeleccionada = '';

  function cargarEstudiantes(claseId) {
    $('#detalle-estudiantes').html('<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando estudiantes...</p>');

    $.ajax({
      url: 'obtener_estudiantes.php',
      method: 'GET',
      data: { clase_id: claseId },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          mostrarEstudiantes(response.estudiantes, response.clase);
        } else {
          $('#detalle-estudiantes').html('<p class="text-danger">' + response.message + '</p>');
        }
      },
      error: function () {
        $('#detalle-estudiantes').html('<p class="text-danger">Error al cargar los estudiantes.</p>');
      }
    });
  }

  function mostrarEstudiantes(estudiantes, clase) {
    var html = '<h6>Clase: ' + clase.asignatura + '</h6>';
    html += '<h6>Docente: ' + clase.docente + '</h6>';
    html += '<h6>Sección: ' + clase.seccion + '</h6>';
    html += '<hr>';

    if (estudiantes.length > 0) {
      html += '<div class="table-responsive">';
      html += '<table class="table table-striped table-hover">';
      html += '<thead><tr><th>#</th><th>Nombre Completo</th><th>Acción</th></tr></thead>';
      html += '<tbody>';
      estudiantes.forEach(function (estudiante, index) {
        html += '<tr>';
        html += '<td>' + (index + 1) + '</td>';
        html += '<td>' + estudiante.apellidos + ', ' + estudiante.nombres + '</td>';
        html += '<td><button class="btn btn-outline-danger btn-sm btn-remove-estudiante" data-estudiante-id="' + estudiante.id + '" title="Eliminar estudiante"><i class="fas fa-trash"></i></button></td>';
        html += '</tr>';
      });
      html += '</tbody></table>';
      html += '</div>';
    } else {
      html += '<p>No hay estudiantes asignados a esta clase.</p>';
    }

    html += '<div class="mt-3">';
    html += '<a href="agregar_estudiantes.php?clase_id=' + clase.id + '" class="btn btn-primary">Agregar Estudiantes</a>';
    html += '</div>';

    $('#detalle-estudiantes').html(html);
  }

  $('#lista-clases').on('click', '.list-group-item', function (e) {
    e.preventDefault();
    $('.list-group-item').removeClass('active');
    $(this).addClass('active');
    claseSeleccionadaId = $(this).data('clase-id');
    cargarEstudiantes(claseSeleccionadaId);
  });

  // Manejador de evento para el botón de eliminar
  $(document).on('click', '.btn-remove-estudiante', function (e) {
    e.preventDefault();
    estudianteAEliminarId = $(this).data('estudiante-id');
    var nombreEstudiante = $(this).closest('tr').find('td:eq(1)').text();

    // Actualizar el contenido del modal
    $('#studentName').text(nombreEstudiante);
    $('#className').text(nombreClaseSeleccionada);

    // Mostrar el modal
    $('#confirmarEliminarModal').modal('show');
  });

  // Manejador para el botón de confirmar eliminación en el modal
  $('#btnConfirmarEliminar').on('click', function () {
    if (estudianteAEliminarId) {
      eliminarEstudianteDeClase(estudianteAEliminarId, claseSeleccionadaId);
      $('#confirmarEliminarModal').modal('hide');
    }
  });

  // Manejadores para los botones de exportación
  $('#exportExcel').on('click', function (e) {
    e.preventDefault();
    if (claseSeleccionadaId) {
      window.location.href = 'exportar_excel.php?clase_id=' + claseSeleccionadaId;
    } else {
      toastr.warning('Por favor, seleccione una clase antes de exportar.');
    }
  });

  $('#exportCSV').on('click', function (e) {
    e.preventDefault();
    if (claseSeleccionadaId) {
      window.location.href = 'exportar_csv.php?clase_id=' + claseSeleccionadaId;
    } else {
      toastr.warning('Por favor, seleccione una clase antes de exportar.');
    }
  });

  function eliminarEstudianteDeClase(estudianteId, claseId) {
    $.ajax({
      url: 'eliminar_estudiante_clase.php',
      method: 'POST',
      data: { estudiante_id: estudianteId, clase_id: claseId },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          toastr.success(response.message);
          cargarEstudiantes(claseId);
        } else {
          toastr.error(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX:", status, error);
        toastr.error('Error al eliminar el estudiante de la clase. Por favor, inténtelo de nuevo.');
      }
    });
  }
});