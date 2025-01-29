<?php
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo = $_SESSION['mensaje_tipo'] ?? 'info';
    unset($_SESSION['mensaje']);
    unset($_SESSION['mensaje_tipo']);
    
    echo "<div class='alert alert-$tipo' role='alert'>$mensaje</div>";
}
?>