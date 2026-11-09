<?php
session_start();

$_SESSION['Authenticated'] = "0";
session_destroy();

// En local: /dulceria/index.php  |  En producción (subdominio): /index.php
$app_path = (in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'])) ? '/dulceria' : '';
header('Location: '.$app_path.'/index.php');
exit;
