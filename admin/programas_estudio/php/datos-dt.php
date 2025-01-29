<?php

session_start();
require_once '../../../config/config.php';

// DB table to use
$table = 'programas_estudio';
// Table's primary key
$primaryKey = 'id';

$columns = array(

  array('db' => 'id', 'dt' => 0),
  array('db' => 'nombre', 'dt' => 1), // Nombre de programa de estudio
  array('db' => 'nombre_corto',  'dt' => 2), // DNombre corto de programa de estudio

);

// SQL server connection information
require '../../includes/conexion.php';

require '../../includes/ssp.class.php';

echo json_encode(
  SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
