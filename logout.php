<?php
session_start();

// Registrar la acción solo si hay un usuario identificado por login
if(isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] != ''){
    include 'functions/funciones.php';
    global $DB2;
    $DB2->query('INSERT INTO control_acciones (id_usuario, accion, fecha, elemento) VALUES ('.(int)$_SESSION['id_usuario'].',"cerrar_session","'.date('Y-m-d H:i:s').'","")');
}

$_SESSION['Authenticated'] = "0";
session_destroy();

header('Location: /dulceria/index.php');
exit;
