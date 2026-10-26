# Instalación en localhost (PHP 8.2)

## Requisitos
- PHP 8.2
- MySQL / MariaDB
- Apache con `mod_rewrite` activado

## Pasos

### 1. Copiar el proyecto
Coloca el proyecto en la carpeta de tu servidor web con la ruta `/dulceria`:

```
/var/www/html/dulceria/       ← Linux (Apache)
C:\xampp\htdocs\dulceria\     ← Windows XAMPP
/Applications/MAMP/htdocs/dulceria/  ← macOS MAMP
```

La URL de acceso será: `http://localhost/dulceria/`

### 2. Base de datos
Crea una base de datos MySQL llamada `dulceria` e importa el backup:

```sql
CREATE DATABASE dulceria CHARACTER SET latin1 COLLATE latin1_swedish_ci;
```

```bash
mysql -u root -p dulceria < backup.sql
```

### 3. Credenciales de BD
El archivo `functions/funciones.php` detecta automáticamente si estás en localhost.
Si tu MySQL local usa credenciales distintas de `root` / (sin contraseña), edita las líneas:

```php
if ($_is_local) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');       // ← cambia aquí
    define('DB_PASS', '');           // ← cambia aquí
    define('DB_DB',   'dulceria');
```

### 4. Apache: activar mod_rewrite
En Linux:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

En el `VirtualHost` o en `/etc/apache2/apache2.conf`, asegúrate de que tienes:
```apache
<Directory /var/www/html>
    AllowOverride All
</Directory>
```

### 5. PHP: extensiones necesarias
Comprueba que tienes activas en `php.ini`:
```
extension=mysqli
extension=mbstring
extension=gd
extension=fileinfo
extension=openssl
```

### 6. Acceso
- Panel principal: `http://localhost/dulceria/panel.php`
- Login: `http://localhost/dulceria/index.php`

---

## Cambios realizados para PHP 8.2

| Problema | Solución |
|---|---|
| `get_magic_quotes_gpc()` eliminada en PHP 8.0 | Shim en `functions/compat_php82.php` |
| `utf8_encode()` / `utf8_decode()` deprecadas en PHP 8.2 | Shim en `functions/compat_php82.php` |
| Librería html2pdf antigua usa `create_function()` (eliminada PHP 8.0) | Redirigida a `spipu/html2pdf` del vendor |
| `E_STRICT` eliminada en PHP 8.0 | Actualizado `error_reporting()` |
| Redirección HTTPS forzada rompía localhost | `.htaccess` excluye localhost de la redirección |
| Config BD apuntaba a producción | Detección automática localhost/producción |
