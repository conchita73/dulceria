<?php
/**
 * Compatibilidad PHP 8.2
 *
 * Shims para funciones eliminadas o deprecadas en PHP 8.x / 8.2
 * Incluir ANTES de cualquier otro require en funciones.php
 */

// -------------------------------------------------------
// get_magic_quotes_gpc() fue eliminada en PHP 8.0
// Siempre devuelve false (las magic quotes llevan años desactivadas)
// -------------------------------------------------------
if (!function_exists('get_magic_quotes_gpc')) {
    function get_magic_quotes_gpc(): bool {
        return false;
    }
}

if (!function_exists('get_magic_quotes_runtime')) {
    function get_magic_quotes_runtime(): bool {
        return false;
    }
}

if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime(bool $new_setting): bool {
        return false;
    }
}

// -------------------------------------------------------
// utf8_encode() / utf8_decode() deprecadas en PHP 8.2
// Equivalentes exactos usando mbstring
// -------------------------------------------------------
if (!function_exists('utf8_encode')) {
    function utf8_encode(string $string): string {
        return mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
    }
}

if (!function_exists('utf8_decode')) {
    function utf8_decode(string $string): string {
        return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    }
}
