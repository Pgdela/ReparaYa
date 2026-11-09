<?php
requiereAdmin();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de reparaciones - ReparaYa</title>
</head>
<body>

    <h1>Gestión de reparaciones</h1>

    <p>Bienvenido, <?= e($_SESSION['nombre']) ?></p>

    <p>
        Desde esta página puedes consultar, asignar técnicos
        y cancelar reparaciones.
    </p>

    <p>
        <a href="index.php?c=auth&a=admin">
            Volver al panel de administración
        </a>
    </p>

    <?php if (!empty($_SESSION['mensaje_admin'])): ?>
        <p style="color: green;">
            <?= e($_SESSION['mensaje_admin']) ?>
        </p>
        <?php unset($_SESSION['mensaje_admin']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['mensaje'])): ?>
        <p>
            <?= e($_SESSION['mensaje']) ?>
        </p>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <h2>Listado de reparaciones</h2>

    <?php if (empty($incidencias)): ?>

        <p>No hay reparaciones registradas.</p>

    <?php else: ?>

        <table border="1" cellpadding="8">

            <tr>
                <th>Localizador</th>
                <th>Cliente</th>
                <th>Tipo de reparación</th>
                <th>Fecha del servicio</th>
                <th>Urgencia</th>
                <th>Estado</th>
                <th>Asignar técnico</th>
                <th>Cancelar</th>
                <th>Detalles</th>
            </tr>

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

                    <td><?= e($incidencia['tipo_urgencia']) ?></td>

                    <td><?= e($incidencia['estado']) ?></td>

                    <td>

                        <?php if (
                            in_array(
                                $incidencia['estado'],
                                ['Pendiente', 'Asignada'],
                                true
                            )
                        ): ?>

                            <?php if (!empty($tecnicos)): ?>

                                <form
                                    method="POST"
                                    action="index.php?c=incidencias&a=asignar"
                                >

                                    <input
                                        type="hidden"
                                        name="incidencia_id"
                                        value="<?= e($incidencia['id']) ?>"
                                    >

                                    <select name="tecnico_id" required>

                                        <option value="">
                                            Seleccionar técnico
                                        </option>

                                        <?php foreach ($tecnicos as $tecnico): ?>

                                            <option value="<?= e($tecnico['id']) ?>">
                                                <?= e($tecnico['nombre_completo']) ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <button type="submit">
                                        Asignar
                                    </button>

                                </form>

                            <?php else: ?>

                                <p>No hay técnicos disponibles.</p>

                            <?php endif; ?>

                        <?php else: ?>

                            <p>No se puede asignar</p>

                        <?php endif; ?>

                    </td>

                    <td>

                        <?php if (
                            in_array(
                                $incidencia['estado'],
                                ['Pendiente', 'Asignada'],
                                true
                            )
                        ): ?>

                            <form
                                method="POST"
                                action="index.php?c=incidencias&a=cancelar"
                                onsubmit="return confirm('¿Seguro que quieres cancelar esta reparación?');"
                            >

                                <input
                                    type="hidden"
                                    name="incidencia_id"
                                    value="<?= e($incidencia['id']) ?>"
                                >

                                <button type="submit">
                                    Cancelar
                                </button>

                            </form>

                        <?php else: ?>

                            No disponible

                        <?php endif; ?>

                    </td>

                    <td>
                        <a href="index.php?c=incidencias&a=detalleAdmin&id=<?= e($incidencia['id']) ?>">
                            Ver detalles
                        </a>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    <?php endif; ?>

</body>
</html>
