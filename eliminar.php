<?php
// =============================================================
// eliminar.php -> BORRAR un equipo (Delete)
// Solo acepta POST (el botón "Eliminar" de la tabla), así no se
// puede borrar nada con solo entrar a un link.
// =============================================================
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $conexion->prepare('DELETE FROM equipos WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}

header('Location: index.php?ok=eliminado');
exit;
