const clasesList = document.getElementById('clasesList');
let estudiantesTable;
let estudiantesDisponiblesTable;
let claseSeleccionadaId;
let clasesTable;
let claseSeleccionada = null;


document.addEventListener('DOMContentLoaded', function () {
  clasesTable = $('#clasesTable').DataTable({
    language: {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sSearch": "Buscar:",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      "buttons": {
        "copy": "Copiar",
        "excel": "Excel",
        "pdf": "PDF",
        "colvis": "Visibilidad",
        "Edit": "Editar"
      }
    },
    order: [[0, 'asc']], // Ordenar por asignatura de forma ascendente
    responsive: true,
    scrollX: true,
    columnDefs: [
      { targets: '_all', className: 'dt-center' }
    ],
    select: true,
  });

  $('#clasesTable tbody').on('click', 'tr', function () {
    const claseId = $(this).data('clase-id');
    if (claseId) {
      claseSeleccionadaId = claseId;
      cargarEstudiantes(claseSeleccionadaId);
      // Resaltar la fila seleccionada
      $('#clasesTable tbody tr').removeClass('active');
      $(this).addClass('active');
    }
  });
});

function cargarEstudiantes(claseId) {
  console.log(`Cargando estudiantes para la clase ID: ${claseId}`);

  // Actualizar el estilo de la fila seleccionada
  // clasesTable.rows().every(function () {
  //   const rowNode = this.node();
  //   if ($(rowNode).data('clase-id') == claseId) {
  //     $(rowNode).addClass('selected');
  //   } else {
  //     $(rowNode).removeClass('selected');
  //   }
  // });

  fetch(`php/get_estudiantes.php?clase_id=${claseId}`)

    .then(response => {
      console.log('Respuesta recibida:', response);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })

    .then(text => {
      console.log("Texto de respuesta del servidor:", text);
      try {
        return JSON.parse(text);
      } catch (e) {
        console.error("Error al analizar JSON:", e);
        console.error("Texto que causó el error:", text);
        throw new Error("Respuesta del servidor inválida");
      }
    })

    .then(data => {
      console.log("Datos JSON parseados:", data);
      if (!data.success) {
        throw new Error(data.message || "Error desconocido");
      }
      actualizarTablaEstudiantes(data);

    })
    .catch(error => {
      console.error('Error al cargar estudiantes:', error);
      showToastr(`Error al cargar estudiantes: ${error.message}. Por favor, revise la consola para más detalles.`, 'error');
    });
}

function actualizarTablaEstudiantes(data) {
  if (estudiantesTable) {
    estudiantesTable.destroy();
  }

  // Actualizar la información de la clase seleccionada
  claseSeleccionada = data.clase;


  const spanInicial = document.querySelector('#listaEstudiantes span');
  const tableHead = document.querySelector('#estudiantesTable thead');
  const tableBody = document.querySelector('#estudiantesTable tbody');

  spanInicial.innerHTML = '';
  // Crear encabezados de la tabla
  let headerRow = '<tr><th>N°</th><th>Nombre completo</th><th>DNI</th></tr>';

  tableHead.innerHTML = headerRow;

  // Llenar el cuerpo de la tabla
  tableBody.innerHTML = data.estudiantes.map((estudiante, index) => {
    let row = `
      <tr>
        <td>${index + 1}</td>
        <td>${estudiante.apellidos}, ${estudiante.nombres}</td>
        <td>${estudiante.dni}</td>
       
      </tr>'`;
    return row;
  }).join('');

  estudiantesTable = $('#estudiantesTable').DataTable({
    language: {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sSearch": "Buscar:",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      "buttons": {
        "copy": "Copiar",
        "excel": "Excel",
        "pdf": "PDF",
        "colvis": "Visibilidad",
        "Edit": "Editar"
      }
    },
    order: [[1, 'asc']], // Ordenar por nombre del estudiante de forma ascendente
    responsive: true,
    scrollX: true,

    layout: {
      top2Start: {
        buttons: [
          {
            text: 'Agregar', className: 'btn btn-primary',
            action: function (e, dt, node, config) {
              mostrarEstudiantesDisponibles();
            }
          },
          {
            text: 'Eliminar', className: 'btn btn-danger',
            action: function (e, dt, node, config) {
              eliminarEstudiantesSeleccionados();
            }
          },
          {
            extend: 'collection',
            text: 'Exportar',
            className: 'btn btn-secondary',
            buttons: [
              {
                extend: 'excel', 
                text: 'Excel' 
              },
              {
                extend: 'pdfHtml5',
                text: 'PDF',
                // className: 'btn btn-danger',
                customize: function (doc) {
                  // Personalizar el PDF
                  doc.content.splice(0, 0, {
                    text: [
                      { text: 'Unidad Didáctica: ' + claseSeleccionada.asignatura + '\n', style: 'subheader' },
                      { text: 'Docente: ' + claseSeleccionada.docente + '\n', style: 'subheader' },
                      { text: 'Sección: ' + claseSeleccionada.seccion + '\n\n', style: 'subheader' }
                    ],
                    margin: [0, 0, 0, 12]
                  });

                  // Ajustar el ancho de las columnas
                  doc.content[1].table.widths = ['10%', '*', '20%'];
                },
                title: 'Lista de Estudiantes',
                filename: 'lista_estudiantes_' + new Date().toISOString().split('T')[0],
                exportOptions: {
                  columns: [0, 1, 2]
                },
                orientation: 'portrait',
                pageSize: 'A4',
                customize: function (doc) {
                  // Estilos y configuraciones adicionales
                  doc.defaultStyle.fontSize = 10;
                  doc.styles.tableHeader.fontSize = 12;
                  doc.styles.title.fontSize = 14;
                  doc.styles.tableHeader.alignment = 'left';
                  doc.styles.subheader = {
                    fontSize: 11,
                    bold: true
                  };

                  // Información de la clase
                  doc.content.splice(1, 0, {
                    text: [
                      { text: 'Unidad Didáctica: ' + claseSeleccionada.asignatura + '\n', style: 'subheader' },
                      { text: 'Docente: ' + claseSeleccionada.docente + '\n', style: 'subheader' },
                      { text: 'Sección: ' + claseSeleccionada.seccion + '\n\n', style: 'subheader' }
                    ],
                    margin: [0, 0, 0, 12]
                  });

                  // Ajustar el ancho de las columnas
                  doc.content[2].table.widths = ['10%', '*', '20%'];
                }
              }

            ]
          }
        ]
      }
    }
  });
}

function mostrarEstudiantesDisponibles() {
  fetch(`php/get_estudiantes_disponibles.php?clase_id=${claseSeleccionadaId}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })
    .then(text => {
      console.log("Raw server response:", text);
      try {
        return JSON.parse(text);
      } catch (e) {
        console.error("JSON parsing error:", e);
        throw new Error("Invalid JSON response from server");
      }
    })
    .then(data => {
      console.log("Datos JSON parseados:", data);
      if (!data.success) {
        // console.error(data.error);
        throw new Error(data.message || "Error desconocido");

      }
      actualizarTablaEstudiantesDisponibles(data.data.estudiantes);
      $('#agregarEstudiantesModal').modal('show');


    })
    .catch(error => {
      console.error('Error:', error);
      showToastr('Error al cargar estudiantes disponibles. Por favor, intente de nuevo.', 'error');
    });
}


function actualizarTablaEstudiantesDisponibles(estudiantes) {
  if (estudiantesDisponiblesTable) {
    estudiantesDisponiblesTable.destroy();
  }

  const tableHead = document.querySelector('#estudiantesDisponiblesTable thead');
  const tableBody = document.querySelector('#estudiantesDisponiblesTable tbody');

  let headerRow = `
  <tr>
      <th>Nombre completo</th>
      <th class="text-center">DNI</th>
      <th class="text-center">Seleccionar</th>
  </tr>
  `;

  tableHead.innerHTML = headerRow;
  // Llenar el cuerpo de la tabla
  tableBody.innerHTML = estudiantes.map((estudiante) => {
    return `
      <tr>
         <td>${estudiante.apellidos}, ${estudiante.nombres}</td>
         <td class="text-center">${estudiante.dni}</td>
        <td class="text-center"><input type="checkbox" class="estudiante-checkbox" data-estudiante-id="${estudiante.id}"></td>
      </tr>`;
  }).join('');

  $('#agregarEstudiantesModal').on('shown.bs.modal', function () {
    if (!$.fn.DataTable.isDataTable('#estudiantesDisponiblesTable')) {
      estudiantesDisponiblesTable = $('#estudiantesDisponiblesTable').DataTable({
        language: {
          "sProcessing": "Procesando...",
          "sLengthMenu": "Mostrar _MENU_ registros",
          "sZeroRecords": "No se encontraron resultados",
          "sEmptyTable": "Ningún dato disponible en esta tabla",
          "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
          "sSearch": "Buscar:",
          "sInfoThousands": ",",
          "sLoadingRecords": "Cargando...",
          "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          },
          "buttons": {
            "copy": "Copiar",
            "excel": "Excel",
            "pdf": "PDF",
            "colvis": "Visibilidad",
            "Edit": "Editar"
          }
        },
        columns: [
          { name: 'fullname' },
          { name: 'dni' },
          { name: 'seleccion' },
        ],
        order: {
          name: 'fullname',
          dir: 'asc'
        }, // Ordenar por fullname asc
        responsive: true,
        scrollX: true,

      });
    } else {
      estudiantesDisponiblesTable.columns.adjust();
    }
  });
}

document.getElementById('btnGuardarEstudiantes').addEventListener('click', function () {
  const estudiantesSeleccionados = Array.from(document.querySelectorAll('.estudiante-checkbox:checked')).map(cb => cb.getAttribute('data-estudiante-id'));

  if (estudiantesSeleccionados.length === 0) {
    toastr.warning('Por favor, seleccione al menos un estudiante.');
    return;
  }

  fetch('php/agregar_estudiantes.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      clase_id: claseSeleccionadaId,
      estudiantes: estudiantesSeleccionados
    }),
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        $('#agregarEstudiantesModal').modal('hide');
        cargarEstudiantes(claseSeleccionadaId);

        showToastr('Los estudiantes han sido agregados exitosamente', 'success');
        // Mostrar notificación toastr de éxito

      } else {
        showToastr('Error al agregar estudiantes: ' + data.message, 'Error');
      }
    })
    .catch((error) => {
      console.error('Error:', error);
      toastr.error('Ha ocurrido un error inesperado', 'Error');
    });
});

function eliminarEstudiantesSeleccionados() {
  const estudiantesSeleccionados = estudiantesTable.rows('.selected').data().toArray().map(row => row[2]);
  if (estudiantesSeleccionados.length === 0) {
    showToastr('Por favor, seleccione al menos un estudiante para eliminar.', 'warning');
    return;
  }

  Swal.fire({
    title: '¿Está seguro?',
    text: "¿Desea eliminar los estudiantes seleccionados de esta unidad didáctica?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch('php/eliminar_estudiantes.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          clase_id: claseSeleccionadaId,
          estudiantes: estudiantesSeleccionados
        }),
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire(
              '¡Eliminados!',
              'Los estudiantes han sido eliminados correctamente.',
              'success'
            ).then(() => {
              cargarEstudiantes(claseSeleccionadaId);
            });
          } else {
            Swal.fire(
              'Error',
              'Error al eliminar estudiantes: ' + data.message,
              'error'
            );
          }
        })
        .catch((error) => {
          console.error('Error:', error);
          Swal.fire(
            'Error',
            'Ha ocurrido un error inesperado',
            'error'
          );
        });
    }
  });
}

// Enable row selection for estudiantesTable
$('#estudiantesTable tbody').on('click', 'tr', function () {
  $(this).toggleClass('selected');
});

document.dispatchEvent(new Event('initDocentesDashboardLoaded'));