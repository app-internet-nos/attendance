<?php
header('Content-Type: application/json');


require_once '../../../config/config.php';

$type = $_GET['type'] ?? '';

try {
    $conn = conectarDB();
    $data = [];

    if ($type === 'programas_estudio') {
        $query = "SELECT id, nombre FROM programas_estudio";
    } elseif ($type === 'ciclos') {
        $query = "SELECT id, nombre FROM ciclos";
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
?>
