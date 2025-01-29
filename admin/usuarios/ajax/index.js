function initializeDataTable(selector, ajaxUrl, languageConfig) {
  return new DataTable(selector, {
    ajax: ajaxUrl,
    processing: true,
    serverSide: true,
    rowId: 'id',
    select: {
      style: 'single',
      rows: null
    },
    columnDefs: [{ targets: [0], visible: false, searchable: false }],
    language: languageConfig,

    layout: {
      top2Start: {
        buttons: [
          {
            text: 'Crear', className: 'btn btn-primary',
            action: function (e, dt, node, config) {
              crear(); // Llama a la función que muestra el formulario modal
            }
          },
          {
            text: 'Crear Masivo', className: 'btn btn-success',
            action: function (e, dt, node, config) {
              crearMasivo();
            }
          },
          {
            text: 'Editar', className: 'btn btn-warning',
            action: function (e, dt, node, config) {
              handleButtonAction(dt, editar, 'Editar');
            }
          },
          {
            text: 'Eliminar', className: 'btn btn-danger',
            action: function (e, dt, node, config) {
              handleButtonAction(dt, eliminar, 'Eliminar');
            }
          },

          {
            text: 'View', className: 'btn btn-info',
            action: function (e, dt, node, config) {
              handleButtonAction(dt, ver, 'Ver los detalles');
            }
          },

          // Botones de exportación
          {
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-secondary',
            buttons: [
              { extend: 'copy', text: 'Copiar' },
              { extend: 'excel', text: 'Excel' },
              { extend: 'csv', text: 'CSV' },
              { extend: 'pdf', text: 'PDF' },
              { extend: 'print', text: 'Imprimir' }
            ]
          }
        ]
      }
    },
  });
}

function toggleRowSelection(table, rowSelector, buttonIndexes) {
  $(rowSelector).on('click', 'tr', function () {
    $(this).toggleClass('selected');
    var selectedRows = table.rows('.selected').count();
    buttonIndexes.forEach(index => {
      var button = table.button(index);
      selectedRows > 0 ? button.enable() : button.disable();
    });
  });
}

function disableButtons(table, buttonIndexes) {
  buttonIndexes.forEach(index => {
    table.button(index).disable();
  });
}

// Configuración de idioma
var languageConfig = {
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

};

// Función genérica para manejar acciones de botones
function handleButtonAction(dt, action, actionName) {
  var selectedData = dt.rows({ selected: true }).data();
  if (selectedData.length === 1) {
    var rowData = selectedData[0];
    var id = rowData[0]; // Asumiendo que el ID está en la primera columna (índice 0)
    action(id);
  } else {
    Swal.fire(
      `Seleccione un usuario`,
      `Debe seleccionar un usuario para ${actionName.toLowerCase()}.`,
      'warning'
    );
  }
}