<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="plantilla_usuarios.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['username', 'password', 'apellidos', 'nombres', 'role', 'status', 'email', 'dni']);
fputcsv($output, ['juan.perez', 'contraseña123', 'Pérez', 'Juan', 'estudiante', 'activo', 'juan.perez@example.com', '12345678']);
fputcsv($output, ['maria.garcia', 'clave456', 'García', 'María', 'docente', 'activo', 'maria.garcia@example.com', '87654321']);
fputcsv($output, ['admin.sistema', 'admin789', 'Admin', 'Sistema', 'admin', 'activo', 'admin@example.com', '11223344']);
fclose($output);