// Configuration for language (example)
const languageConfig = {
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

const createDataTableConfig = (ajaxUrl, languageConfig) => ({
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
      buttons: createButtonConfig()
    }
  },
});

const createButtonConfig = () => [
  {
    text: 'Crear', className: 'btn btn-primary',
    action: (e, dt, node, config) => crear()
  },
  {
    text: 'Editar', className: 'btn btn-warning',
    action: (e, dt, node, config) => handleButtonAction(dt, editar, 'Editar')
  },
  {
    text: 'Eliminar', className: 'btn btn-danger',
    action: (e, dt, node, config) => handleButtonAction(dt, eliminar, 'Eliminar')
  },
  {
    text: 'View', className: 'btn btn-info',
    action: (e, dt, node, config) => handleButtonAction(dt, ver, 'Ver los detalles')
  },
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
];

const initializeDataTable = (selector, ajaxUrl, languageConfig) => {
  return new DataTable(selector, createDataTableConfig(ajaxUrl, languageConfig));
};

const toggleRowSelection = (table, rowSelector, buttonIndexes) => {
  $(rowSelector).on('click', 'tr', function () {
    $(this).toggleClass('selected');
    const selectedRows = table.rows('.selected').count();

    buttonIndexes.forEach(index => {
      const button = table.button(index);
      selectedRows > 0 ? button.enable() : button.disable();
    });
  });
};

const disableButtons = (table, buttonIndexes) => {
  buttonIndexes.forEach(index => {
    table.button(index).disable();
  });
};

// Generic function to handle button actions
const handleButtonAction = (dt, action, actionName) => {
  const selectedData = dt.rows({ selected: true }).data();
  if (selectedData.length === 1) {
    const rowData = selectedData[0];
    const id = rowData[0]; // Assuming ID is in the first column (index 0)
    action(id);
  } else {
    Swal.fire(
      `Seleccione una sección`,
      `Debe seleccionar una sección para ${actionName.toLowerCase()}.`,
      'warning'
    );
  }
};

// Helper function to capitalize the first letter of a string
const ucfirst = (str) => str.charAt(0).toUpperCase() + str.slice(1);