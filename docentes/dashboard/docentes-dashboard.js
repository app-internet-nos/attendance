

document.addEventListener('DOMContentLoaded', function () {
  let asistenciasTable;
  let clasesTable;
  let claseSeleccionada = null;
  let fullNameDocente = window.phpData.fullNameDocente;

  // Inicializar la tabla de clases
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
      }
    },
    order: [[0, 'asc']], // Ordenar por asignatura de forma ascendente
    responsive: true,
    scrollX: true,
    columnDefs: [
      { targets: '_all', className: 'dt-center' }
    ],

    layout: {
      top2Start: {
        buttons: [

          {
            extend: 'collection',
            text: 'Exportar',
            className: 'btn btn-secondary',
            buttons: [
              {
                extend: 'excelHtml5',
                text: 'Excel',
                title: 'Unidades didácticas',
                messageTop: fullNameDocente,
                filename: 'unidases_didáctica_docente_' + new Date().toISOString().split('T')[0],
                exportOptions: {
                  columns: ':visible', // Exportar solo las columnas visibles
                  format: {
                    body: function (data, row, column, node) {
                      // Modificar los datos si es necesario
                      return data;
                    }
                  }
                },
                customize: function (xlsx) {
                  // Personalización del archivo Excel (opcional)
                  var sheet = xlsx.xl.worksheets['sheet1.xml'];
                  sheet.getElementsByTagName('col').length - 1
                  // $('row c[r="A1"]', sheet).attr('s', '51');
                  $('row c[r="A2"]', sheet).attr('s', '51').attr('s', '2');
                  $('row[r=3] c', sheet).attr('s', '7');
                  //$('row c[r^="C"]', sheet).attr('s', '2'); // Ejemplo de estilo en la columna C
                }
              },



              {
                extend: 'pdfHtml5',
                text: 'PDF',
                // className: 'btn btn-danger',
                title: 'Unidades didácticas' + '\n\n' + fullNameDocente,
                filename: 'unidades_didacticas_docente_' + new Date().toISOString().split('T')[0],
                exportOptions: {
                  columns: ':visible'
                },
                customize: function (doc) {
                  doc.defaultStyle.fontSize = 8;
                  doc.styles.tableHeader.fontSize = 9;
                  doc.styles.tableHeader.alignment = 'left';
                  doc.content[1].table.widths =
                    Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                }
              }
            ]
          },
        ]
      }
    },
    select: true,
  });

  $('#clasesTable tbody').on('click', 'tr', function () {
    const claseId = $(this).data('clase-id');
    if (claseId) {
      cargarAsistencias(claseId);

      // Resaltar la clase seleccionada
      clasesTable.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });

  function cargarAsistencias(claseId) {
    const fila = clasesTable.row(`[data-clase-id="${claseId}"]`).data();
    claseSeleccionada = {
      asignatura: fila[0],
      seccion: fila[1],
    };

    fetch(`get_asistencias.php?clase_id=${claseId}`)
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error(data.error);
        } else {
          actualizarTablaAsistencias(data);
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function actualizarTablaAsistencias(data) {
    if (asistenciasTable) {
      asistenciasTable.destroy();
    }

    const spanInicial = document.querySelector('#listaAsistencias span');
    const tableHead = document.querySelector('#asistenciasTable thead');
    const tableBody = document.querySelector('#asistenciasTable tbody');


    spanInicial.innerHTML = '';
    // Crear encabezados de la tabla
    let headerRow = '<tr><th>N°</th><th>Estudiante</th>';
    data.fechas.forEach(fecha => {
      headerRow += `<th>${fecha}</th>`;
    });
    headerRow += '</tr>';
    tableHead.innerHTML = headerRow;

    // Llenar el cuerpo de la tabla
    tableBody.innerHTML = data.estudiantes.map((estudiante, index) => {
      let row = `<tr>
        <td>${index + 1}</td>
        <td>${estudiante.apellidos}, ${estudiante.nombres}</td>`;
      data.fechas.forEach(fecha => {
        const asistencia = estudiante.asistencias[fecha] || '--';
        row += `<td>${asistencia}</td>`;
      });
      row += '</tr>';
      return row;
    }).join('');

    asistenciasTable = $('#asistenciasTable').DataTable({
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
              extend: 'collection',
              text: 'Exportar',
              className: 'btn btn-secondary',
              buttons: [

                {
                  extend: 'excelHtml5',
                  text: 'Excel',
                  title: 'Asistencias',
                  messageTop: claseSeleccionada.asignatura + ': '+ fullNameDocente + ' (' + claseSeleccionada.seccion + ')',
                  filename: 'asistencias_clases_docente_' + new Date().toISOString().split('T')[0],
                  exportOptions: {
                    columns: ':visible', // Exportar solo las columnas visibles
                    format: {
                      body: function (data, row, column, node) {
                        // Modificar los datos si es necesario
                        return data;
                      }
                    }
                  },
                  customize: function (xlsx) {
                    // Personalización del archivo Excel (opcional)
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    sheet.getElementsByTagName('col').length - 1
                    // $('row c[r="A1"]', sheet).attr('s', '51');
                    $('row c[r="A2"]', sheet).attr('s', '51').attr('s', '2');
                    $('row[r=3] c', sheet).attr('s', '7');
                    //$('row c[r^="C"]', sheet).attr('s', '2'); // Ejemplo de estilo en la columna C
                  }
                },

                {
                  extend: 'pdfHtml5',
                  text: 'PDF',
                  title: 'Asistencias',
                  filename: 'asistencias_clases_docente_' + new Date().toISOString().split('T')[0],
                  exportOptions: {
                    columns: ':visible'
                  },
                  // orientation: 'portrait',
                  orientation: 'landscape',
                  pageSize: 'A4',
                  customize: function (doc) {
                    doc.defaultStyle.fontSize = 10;
                    doc.styles.tableHeader.fontSize = 12;
                    doc.styles.title.fontSize = 14;
                    doc.styles.tableHeader.alignment = 'left';
                    doc.styles.subheader = {
                      fontSize: 11,
                      bold: true
                    };

                    // Añadir información de la clase al inicio del documento
                    doc.content.splice(0, 0, {
                      text: [
                        { text: 'Unidad Didáctica: ' + claseSeleccionada.asignatura + '\n', style: 'subheader' },
                        { text: 'Docente: ' + fullNameDocente + '\n', style: 'subheader' },
                        { text: 'Sección: ' + claseSeleccionada.seccion + '\n\n', style: 'subheader' }
                        
                      ],
                      margin: [0, 0, 0, 12]
                    });

                    // Función para encontrar la tabla en el documento
                    function findTable(content) {
                      for (var i = 0; i < content.length; i++) {
                        if (content[i].table && content[i].table.body) {
                          return content[i].table;
                        }
                      }
                      return null;
                    }

                    // Encontrar la tabla
                    var table = findTable(doc.content);

                    if (table) {
                      // Calcular el número de columnas
                      var numColumns = table.body[0].length;

                      // Crear un array de anchos de columna
                      var columnWidths = ['5%', '25%']; // Porcentajes para 'Id' y 'Estudiante'
                      var remainingWidth = (100 - 30) / (numColumns - 2); // Distribuir el resto entre las columnas de fechas
                      for (var i = 2; i < numColumns; i++) {
                        columnWidths.push(remainingWidth + '%');
                      }

                      // Asignar los anchos a la tabla
                      table.widths = columnWidths;

                      // Ajustar el estilo de la tabla para asegurar que ocupe todo el ancho disponible
                      table.layout = 'lightHorizontalLines';
                      table.dontBreakRows = true;
                    } else {
                      console.warn('No se pudo encontrar la tabla en el documento PDF');
                    }
                  }
                }

              ]
            },
          ]
        }
      }
    });
  }

  document.dispatchEvent(new Event('initDocentesDashboardLoaded'));
});