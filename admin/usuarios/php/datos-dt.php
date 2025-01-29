<?php

session_start();
require_once '../../../config/config.php';

// DB table to use
$table = 'usuarios';
// Table's primary key
$primaryKey = 'id';

$columns = array(
  // array('db' => 'id', 'dt' => 0, 'formatter' => function ($d, $row) {
  //   return '<span class="d-none">' . $d . '</span>';
  // }), // Columna oculta para el ID


  array('db' => 'id', 'dt' => '0'),
  array('db' => 'username', 'dt' => 1), // Nombre de usuario
  array('db' => 'apellidos',  'dt' => 2), // Apellidos
  array('db' => 'nombres',   'dt' => 3), // Nombres
  array('db' => 'role', 'dt' => 4, 'formatter' => function ($d, $row) {
    // Capitalizar el rol para mostrarlo en un formato más presentable
    return ucfirst($d);
  }),
  // array('db' => 'status',     'dt' => 4),
  array('db' => 'status', 'dt' => 5, 'formatter' => function ($d, $row) {
    // Mostrar estado con colores o etiquetas
    if ($d == 'activo') {
      return '<span class="badge bg-success">Activo</span>';
    } else {
      return '<span class="badge bg-danger">Inactivo</span>';
    }
  }),

);

// SQL server connection information
require '../../includes/conexion.php';

require '../../includes/ssp.class.php';

echo json_encode(
  SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
