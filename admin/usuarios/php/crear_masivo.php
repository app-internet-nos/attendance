<?php
session_start();
require_once '../../../config/config.php';

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función para registrar errores
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, '../logs/error.log');
}

try {
    $conn = conectarDB();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] != UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo: ' . $_FILES['csv_file']['error']);
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, "r");
        
        if ($handle !== FALSE) {
            $conn->begin_transaction();
            $successCount = 0;
            $errorCount = 0;
            $lineCount = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $lineCount++;
                if ($lineCount == 1) continue; // Skip header row

                $username = trim($data[0]);
                $password = trim($data[1]);
                $apellidos = trim($data[2]);
                $nombres = trim($data[3]);
                $role = trim($data[4]);
                $status = trim($data[5]);
                $email = trim($data[6]);
                $dni = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

                if (empty($username) || empty($password) || empty($apellidos) || empty($nombres) || empty($role) || empty($status) || empty($email) || empty($dni)) {
                    logError("Línea $lineCount: Datos incompletos");
                    $errorCount++;
                    continue;
                }

                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $query = "INSERT INTO usuarios (username, password, role, status, email, apellidos, nombres, dni) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    logError("Línea $lineCount: Error en la preparación de la consulta: " . $conn->error);
                    $errorCount++;
                    continue;
                }

                $stmt->bind_param('ssssssss', $username, $hashed_password, $role, $status, $email, $apellidos, $nombres, $dni);

                if ($stmt->execute()) {
                    $successCount++;
                } else {
                    logError("Línea $lineCount: Error al ejecutar la consulta: " . $stmt->error);
                    $errorCount++;
                }

                $stmt->close();
            }

            fclose($handle);

            if ($errorCount == 0) {
                $conn->commit();
                echo json_encode(['success' => true, 'message' => "Se crearon $successCount usuarios exitosamente."]);
            } else {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => "Se intentó crear $successCount usuarios, pero hubo $errorCount errores. No se realizó ningún cambio."]);
            }
        } else {
            throw new Exception('Error al leer el archivo CSV.');
        }

        $conn->close();
    } else {
        throw new Exception('Método de solicitud no permitido.');
    }
} catch (Exception $e) {
    logError('Excepción capturada: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}