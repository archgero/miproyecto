<?php
// =============================================================
// index.php -> PÁGINA PRINCIPAL
//   - Formulario para REGISTRAR un equipo nuevo  (Create)
//   - Tabla que MUESTRA los equipos guardados     (Read)
//   - Botones para EDITAR (Update) y ELIMINAR    (Delete)
// =============================================================
require 'conexion.php';

$mensaje = '';

// ---- 1) Si llegó el formulario (método POST), guardamos el equipo ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // trim() saca los espacios de más al principio y al final
    $nombre    = trim($_POST['nombre'] ?? '');
    $tipo      = trim($_POST['tipo'] ?? '');
    $marca     = trim($_POST['marca'] ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $estado    = trim($_POST['estado'] ?? '');

    if ($nombre === '' || $tipo === '' || $marca === '' || $ubicacion === '' || $estado === '') {
        $mensaje = 'Completá todos los campos.';
    } else {
        // "Consulta preparada": los ? se rellenan después con los datos.
        // Así nadie puede meter SQL malicioso desde el formulario.
        $sql  = 'INSERT INTO equipos (nombre, tipo, marca, ubicacion, estado) VALUES (?, ?, ?, ?, ?)';
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('sssss', $nombre, $tipo, $marca, $ubicacion, $estado); // s = texto
        $stmt->execute();

        // Volvemos a la misma página: así, si apretás F5,
        // no se guarda el mismo equipo dos veces
        header('Location: index.php?ok=creado');
        exit;
    }
}

// Mensajes que llegan por la URL después de cada acción
$avisos = [
    'creado'    => '✅ Equipo registrado correctamente.',
    'editado'   => '✅ Equipo modificado correctamente.',
    'eliminado' => '🗑️ Equipo eliminado.',
];
if (isset($_GET['ok'], $avisos[$_GET['ok']])) {
    $mensaje = $avisos[$_GET['ok']];
}

// ---- 2) Traemos todos los equipos para mostrarlos en la tabla ----
$resultado = $conexion->query('SELECT * FROM equipos ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Equipos - Centro de Cómputos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>🖥️ Registro de Equipos</h1>
        <p>Centro de Cómputos · versión 2.0</p>
    </header>

    <main>
        <?php if ($mensaje): ?>
            <p class="mensaje"><?= e($mensaje) ?></p>
        <?php endif; ?>

        <!-- ===== Formulario para registrar ===== -->
        <section class="tarjeta">
            <h2>Registrar equipo</h2>
            <form method="POST" action="index.php" class="formulario">
                <label>Nombre del equipo
                    <input type="text" name="nombre" placeholder="Ej: PC-LAB-02" required>
                </label>
                <label>Tipo
                    <select name="tipo" required>
                        <?php foreach ($TIPOS as $t): ?>
                            <option><?= e($t) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Marca
                    <input type="text" name="marca" placeholder="Ej: Lenovo" required>
                </label>
                <label>Ubicación
                    <input type="text" name="ubicacion" placeholder="Ej: Laboratorio 2" required>
                </label>
                <label>Estado
                    <select name="estado" required>
                        <?php foreach ($ESTADOS as $es): ?>
                            <option><?= e($es) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit">Guardar</button>
            </form>
        </section>

        <!-- ===== Tabla con los equipos guardados ===== -->
        <section class="tarjeta">
            <h2>Equipos registrados (<?= $resultado->num_rows ?>)</h2>
            <?php if ($resultado->num_rows === 0): ?>
                <p>Todavía no hay equipos cargados.</p>
            <?php else: ?>
                <div class="tabla-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Nombre</th><th>Tipo</th><th>Marca</th>
                            <th>Ubicación</th><th>Estado</th><th>Registrado</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= e($fila['id']) ?></td>
                                <td><?= e($fila['nombre']) ?></td>
                                <td><?= e($fila['tipo']) ?></td>
                                <td><?= e($fila['marca']) ?></td>
                                <td><?= e($fila['ubicacion']) ?></td>
                                <td><?= e($fila['estado']) ?></td>
                                <td><?= e($fila['fecha_registro']) ?></td>
                                <td class="acciones">
                                    <a class="boton" href="editar.php?id=<?= e($fila['id']) ?>">Editar</a>
                                    <!-- Eliminar va por POST y pide confirmación antes -->
                                    <form method="POST" action="eliminar.php"
                                          onsubmit="return confirm('¿Seguro que querés eliminar este equipo?');">
                                        <input type="hidden" name="id" value="<?= e($fila['id']) ?>">
                                        <button type="submit" class="peligro">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>Servidor: <?= e(gethostname()) ?> · Práctico Final SYSADM 2026</footer>
</body>
</html>
