<?php
header('Content-Type: application/json');


require_once '../../../config/config.php';

$type = $_GET['type'] ?? '';

try {
  $conn = conectarDB();
  $data = [];

  if ($type === 'asignaturas') {
    $query = "SELECT id, nombre FROM asignaturas";
  } elseif ($type === 'docentes') {
    $query = "SELECT id,  concat( apellidos, ' ', nombres, ' (', dni, ')' ) AS nombre FROM usuarios WHERE role = 'docente'";
  } elseif ($type === 'secciones') {
    $query = "SELECT s.id, concat(pe.nombre, '-', c.nombre, '-', s.año) AS nombre  
              FROM secciones AS s INNER JOIN programas_estudio AS pe ON s.id_programa_estudio = pe.id
              INNER JOIN ciclos AS c ON s.id_ciclo = c.id";
  } else {
    throw new Exception("Tipo no válido");
  }

  $result = mysqli_query($conn, $query);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }
  } else {
    throw new Exception("Error en la consulta: " . mysqli_error($conn));
  }

  echo json_encode($data);
} catch (Exception $e) {
  echo json_encode(["error" => $e->getMessage()]);
}
