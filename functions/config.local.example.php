<?php
/**
 * Configuración local de base de datos.
 *
 * INSTRUCCIONES:
 *   1. Copia este archivo y llámalo "config.local.php" (en esta misma carpeta).
 *   2. Edita los valores con tus credenciales locales.
 *   3. NO hace falta tocarlo nunca más — git lo ignora completamente.
 *
 * Este archivo (config.local.example.php) SÍ está en git como plantilla.
 * El archivo "config.local.php" está en .gitignore, así que git pull
 * nunca lo sobreescribirá.
 */

// Descomenta y ajusta las líneas que necesites:

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DB',   'dulceria');
