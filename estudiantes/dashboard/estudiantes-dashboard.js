document.addEventListener('DOMContentLoaded', function () {
  let asistenciasTable;
  let clasesTable;
  let claseSeleccionada = null;
  let fullNameEstudiante = window.phpData.fullNameEstudiante;

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
    order: [[3, 'desc'], [2, 'asc'], [0, 'asc']], // Ordenar por año desc, ciclo asc, asignatura asc
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
                messageTop: fullNameEstudiante,
                filename: 'unidases_didáctica_estudiante_' + new Date().toISOString().split('T')[0],
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
                title: 'Unidades didácticas ' + '\n\n' + fullNameEstudiante,
                filename: 'unidades_didacticas_estudiante_' + new Date().toISOString().split('T')[0],
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
          }
        ]
      }
    },
    select: true
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
      docente: fila[2],
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

  // Función para formatear la fecha
  function formatearFecha(fechaString) {
    // Dividir la fecha en sus componentes
    const [anio, mes, dia] = fechaString.split('-');

    // Crear una nueva fecha usando los componentes (mes - 1 porque en JS los meses van de 0 a 11)
    const fecha = new Date(anio, mes - 1, dia);

    // Formatear la fecha
    const diaFormateado = fecha.getDate().toString().padStart(2, '0');
    const mesFormateado = (fecha.getMonth() + 1).toString().padStart(2, '0');
    const anioFormateado = fecha.getFullYear();

    return `${diaFormateado}-${mesFormateado}-${anioFormateado}`;
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
    let headerRow = '<tr><th>N°</th><th>Fecha</th><th>Hora</th></tr>';
    tableHead.innerHTML = headerRow;

    // Llenar el cuerpo de la tabla
    tableBody.innerHTML = data.map((asistencia, index) => `
      <tr>
        <td>${index + 1}</td>
        <td>${formatearFecha(asistencia.fecha)}</td>
        <td>${asistencia.entrada || '----'}</td>
      </tr>
    `).join('');

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
        }
      },
      order: [[1, 'desc']], // Ordenar por fecha de forma descendente
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
                  // className: 'btn btn-success',
                  exportOptions: {
                    columns: ':visible'
                  },
                  title: 'Asistencias',
                  messageTop: claseSeleccionada.asignatura + ': ' + claseSeleccionada.docente + ' (' + claseSeleccionada.seccion + ')',
                  filename: 'asistencias_unidases_didáctica_estudiante_' + new Date().toISOString().split('T')[0],
                  customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var styles = xlsx.xl['styles.xml'];

                    // Agregar un nuevo estilo con wrapText en styles.xml
                    var styleXml = `
                          <xf xfId="0" applyAlignment="1">
                              <alignment wrapText="1"/>
                          </xf>
                      `;
                    var lastStyleIndex = $('cellXfs xf', styles).length; // Obtener el último índice de estilo
                    $('cellXfs', styles).append(styleXml);

                    // Aplicar el estilo recién agregado a las celdas que contienen saltos de línea
                    $('row c[r^="A"]', sheet).each(function () {
                      if ($(this).text().includes('\n')) {
                        $(this).attr('s', lastStyleIndex); // Asignar el nuevo estilo con wrapText
                      }
                    });
                  }
                },
                {
                  extend: 'pdfHtml5',
                  text: 'PDF',
                  // className: 'btn btn-danger',
                  customize: function (doc) {


                    // Ajustar el ancho de las columnas
                    doc.content[1].table.widths = ['10%', '*', '20%'];
                  },
                  title: 'Asistencias',

                  filename: 'asistencias_unidases_didáctica_estudiante_' + new Date().toISOString().split('T')[0],


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
            },
          ]
        }
      }

    });
  }
});