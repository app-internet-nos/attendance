<?php

session_start();
require_once '../../../config/config.php';

// DB table to use
$table = <<<SQL
( 
  SELECT
	cl.id,
	u.dni AS dni,
  concat(u.apellidos, ' ' , u.nombres) as docente,
	a.nombre AS unidad_didactica,
	pe.nombre AS programa_estudio,
	pe.nombre_corto AS programa_estudio_corto,
	c.nombre AS ciclo,
	s.`año` AS `año` 
FROM
	clases AS cl
	INNER JOIN asignaturas AS a ON cl.id_asignatura = a.id
	INNER JOIN usuarios AS u ON cl.id_docente = u.id
	INNER JOIN secciones AS s ON cl.id_seccion = s.id
	INNER JOIN programas_estudio AS pe ON s.id_programa_estudio = pe.id
	INNER JOIN ciclos AS c ON s.id_ciclo = c.id 
  WHERE 	u.role = 'docente'
) temp
SQL;


// Table's primary key
$primaryKey = 'id';

$columns = array(

  array('db' => 'id', 'dt' => 0),
  array('db' => 'unidad_didactica', 'dt' => 1), 
  array('db' => 'programa_estudio',  'dt' => 2), 
  array('db' => 'ciclo',  'dt' => 3), 
  array('db' => 'año',  'dt' => 4), 
  array('db' => 'docente',  'dt' => 5), 


);

// SQL server connection information
require '../../includes/conexion.php';

require '../../includes/ssp.class.php';

echo json_encode(
  SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
