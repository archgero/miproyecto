<?php
// =============================================================
// editar.php -> MODIFICAR un equipo (Update)
//   GET  -> muestra el formulario con los datos actuales
//   POST -> guarda los cambios en la base de datos
// =============================================================
require 'conexion.php';

// El id del equipo viene por la URL (editar.php?id=3) o por el formulario
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0); // (int) lo fuerza a número

// ---- Si llegó el formulario, actualizamos ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $tipo      = trim($_POST['tipo'] ?? '');
    $marca     = trim($_POST['marca'] ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $estado    = trim($_POST['estado'] ?? '');

    if ($id > 0 && $nombre !== '' && $tipo !== '' && $marca !== '' && $ubicacion !== '' && $estado !== '') {
        $sql  = 'UPDATE equipos SET nombre = ?, tipo = ?, marca = ?, ubicacion = ?, estado = ? WHERE id = ?';
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('sssssi', $nombre, $tipo, $marca, $ubicacion, $estado, $id); // i = número entero
        $stmt->execute();

        header('Location: index.php?ok=editado');
        exit;
    }
}

// ---- Buscamos el equipo para rellenar el formulario ----
$stmt = $conexion->prepare('SELECT * FROM equipos WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();

// Si no existe (por ejemplo, ya lo borraron), volvemos al inicio
if (!$equipo) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar equipo - Centro de Cómputos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>🖥️ Registro de Equipos</h1>
        <p>Centro de Cómputos · versión 1.0</p>
    </header>

    <main>
        <section class="tarjeta">
            <h2>Editar equipo #<?= e($equipo['id']) ?></h2>
            <form method="POST" action="editar.php" class="formulario">
                <input type="hidden" name="id" value="<?= e($equipo['id']) ?>">

                <label>Nombre del equipo
                    <input type="text" name="nombre" value="<?= e($equipo['nombre']) ?>" required>
                </label>
                <label>Tipo
                    <select name="tipo" required>
                        <?php foreach ($TIPOS as $t): ?>
                            <option <?= $t === $equipo['tipo'] ? 'selected' : '' ?>><?= e($t) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Marca
                    <input type="text" name="marca" value="<?= e($equipo['marca']) ?>" required>
                </label>
                <label>Ubicación
                    <input type="text" name="ubicacion" value="<?= e($equipo['ubicacion']) ?>" required>
                </label>
                <label>Estado
                    <select name="estado" required>
                        <?php foreach ($ESTADOS as $es): ?>
                            <option <?= $es === $equipo['estado'] ? 'selected' : '' ?>><?= e($es) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit">Guardar cambios</button>
                <a class="boton secundario" href="index.php">Cancelar</a>
            </form>
        </section>
    </main>

    <footer>Servidor: <?= e(gethostname()) ?> · Práctico Final SYSADM 2026</footer>
</body>
</html>
