<?php

session_start();
require_once '../../../config/config.php';

// DB table to use
$table = <<<SQL
( 
SELECT s.id, pe.nombre as name_pe, c.nombre as name_ci, s.año
FROM secciones s
LEFT JOIN programas_estudio pe ON pe.id = s.id_programa_estudio
LEFT JOIN ciclos c ON c.id = s.id_ciclo 
) temp
SQL;


// Table's primary key
$primaryKey = 'id';

$columns = array(

  array('db' => 'id', 'dt' => 0),
  array('db' => 'name_pe', 'dt' => 1), 
  array('db' => 'name_ci',  'dt' => 2), 
  array('db' => 'año',  'dt' => 3), 

);

// SQL server connection information
require '../../includes/conexion.php';

require '../../includes/ssp.class.php';

echo json_encode(
  SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
