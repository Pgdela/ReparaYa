<?php
requiereTecnico();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda del técnico - ReparaYa</title>
</head>
<body>

    <h1>Panel del técnico</h1>

    <p>Bienvenido, <?= e($_SESSION['nombre']) ?></p>

    <p>
        Desde aquí puedes consultar y gestionar tus reparaciones asignadas.
    </p>

    <h2>Mi cuenta</h2>

    <p>
        <a href="index.php?c=auth&a=perfil">
            Mi perfil
        </a>
    </p>

    <p>
        <a href="index.php?c=auth&a=logout">
            Cerrar sesión
        </a>
    </p>

    <hr>

    <h2>Mis reparaciones</h2>

    <?php if (!empty($_SESSION['mensaje_tecnico_panel'])): ?>

        <p>
            <?= e($_SESSION['mensaje_tecnico_panel']) ?>
        </p>

        <?php unset($_SESSION['mensaje_tecnico_panel']); ?>

    <?php endif; ?>

    <?php if (empty($incidencias)): ?>

        <p>No tienes reparaciones asignadas actualmente.</p>

    <?php else: ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Localizador</th>
                    <th>Cliente</th>
                    <th>Especialidad</th>
                    <th>Fecha</th>
                    <th>Horario</th>
                    <th>Urgencia</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($incidencias as $incidencia): ?>

                    <tr>
                        <td><?= e($incidencia['localizador']) ?></td>

                        <td><?= e($incidencia['nombre_cliente']) ?></td>

                        <td><?= e($incidencia['nombre_especialidad']) ?></td>

                        <td>
                            <?= e(date(
                                'd/m/Y H:i',
                                strtotime($incidencia['fecha_servicio'])
                            )) ?>
                        </td>

                        <td><?= e($incidencia['franja_horaria']) ?></td>

                        <td><?= e($incidencia['tipo_urgencia']) ?></td>

                        <td><?= e($incidencia['estado']) ?></td>

                        <td>
                            <a href="index.php?c=tecnicos&a=detalle&id=<?= e($incidencia['id']) ?>">
                                Ver detalles
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>
