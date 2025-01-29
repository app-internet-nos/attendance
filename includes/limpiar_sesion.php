<?php
session_start();
unset($_SESSION['clases']);
unset($_SESSION['dni']);
session_write_close();

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>