<?php
// =============================================================
// conexion.php -> se conecta a la base de datos MariaDB
// Lo usan todas las páginas con: require 'conexion.php';
// =============================================================

// Traemos los datos de conexión (usuario, contraseña, etc.)
require __DIR__ . '/config.php';

// Si algo falla, que PHP tire un error claro en vez de seguir de largo
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // mysqli = la "herramienta" de PHP para hablar con MySQL/MariaDB
    $conexion = new mysqli(DB_HOST, DB_USUARIO, DB_CLAVE, DB_NOMBRE);
    $conexion->set_charset('utf8mb4'); // para que anden tildes y eñes
} catch (mysqli_sql_exception $e) {
    // El detalle del error va al log de Apache, no a la pantalla
    error_log('Error de conexión a la BD: ' . $e->getMessage());
    die('No se pudo conectar a la base de datos. Revisá config.php y que MariaDB esté corriendo.');
}

// Función cortita para mostrar texto de forma segura en el HTML
// (evita que alguien meta código raro en el formulario)
function e($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

// Listas de opciones para los <select> del formulario
$TIPOS   = ['PC de escritorio', 'Notebook', 'Servidor', 'Impresora', 'Router / Switch', 'Otro'];
$ESTADOS = ['Operativo', 'En reparación', 'Fuera de servicio'];
