<?php

session_start();
require_once '../../../config/config.php';

// DB table to use
$table = 'asignaturas';
// Table's primary key
$primaryKey = 'id';

$columns = array(

  array('db' => 'id', 'dt' => 0),
  array('db' => 'nombre', 'dt' => 1), // Nombre de asignatura
  array('db' => 'descripcion',  'dt' => 2), // Descripcion de la asignatura

);

// SQL server connection information
require '../../includes/conexion.php';

require '../../includes/ssp.class.php';

echo json_encode(
  SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
