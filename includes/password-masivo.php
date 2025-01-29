<?php 
require_once 'config/config.php';

try {
    $conn = conectarDB();
    $nuevo_password = 'docente123';
    $password_hash = password_hash($nuevo_password, PASSWORD_DEFAULT);

    $role = 'docente';

    $sql = "UPDATE usuarios SET password = ? WHERE role = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Error preparando la consulta: " . $conn->error);
    }

    $stmt->bind_param("ss", $password_hash, $role);
    if (!$stmt->execute()) {
        throw new Exception("Error ejecutando la consulta: " . $stmt->error);
    }

    echo "Contraseñas actualizadas con éxito para {$stmt->affected_rows} usuarios con rol '{$role}'.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>