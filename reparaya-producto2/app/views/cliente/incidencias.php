<?php
requiereCliente();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis reparaciones - ReparaYa</title>
</head>
<body>

    <h1>Mis reparaciones</h1>

    <?php if (!empty($_SESSION['mensaje'])): ?>
        <p style="color: green;">
            <?= e($_SESSION['mensaje']) ?>
        </p>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <p>Bienvenido, <?= e($_SESSION['nombre']) ?></p>

    <a href="index.php?c=auth&a=cliente">Volver al panel</a>

    <h2>Listado de reparaciones</h2>

    <?php if (empty($incidencias)): ?>

        <p>Todavía no tienes reparaciones registradas.</p>

    <?php else: ?>

        <table border="1">
            <tr>
                <th>Localizador</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php foreach ($incidencias as $incidencia): ?>
                <tr>
                    <td><?= e($incidencia['localizador']) ?></td>
                    <td><?= e($incidencia['descripcion']) ?></td>
                    <td><?= e($incidencia['fecha_servicio']) ?></td>
                    <td><?= e($incidencia['estado']) ?></td>
                    <td>
                        <a href="index.php?c=incidencias&a=detalle&id=<?= e($incidencia['id']) ?>">
                            Ver detalles
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>

</body>
</html>
