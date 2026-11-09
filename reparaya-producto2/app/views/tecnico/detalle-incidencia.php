
<?php
requiereTecnico();
/** @var array<string, mixed> $incidencia */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de reparación - ReparaYa</title>
</head>
<body>

    <h1>Detalle de la reparación</h1>

    <p>
        <a href="index.php?c=tecnicos&a=misIncidencias">
            Volver a mi agenda
        </a>
    </p>

    <h2>Información de la reparación</h2>

    <p><strong>Localizador:</strong> <?= e($incidencia['localizador']) ?></p>
    <p><strong>Cliente:</strong> <?= e($incidencia['nombre_cliente']) ?></p>
    <p><strong>Especialidad:</strong> <?= e($incidencia['nombre_especialidad']) ?></p>
    <p><strong>Descripción:</strong> <?= nl2br(e($incidencia['descripcion'])) ?></p>
    <p><strong>Dirección:</strong> <?= e($incidencia['direccion']) ?></p>
    <p><strong>Teléfono:</strong> <?= e($incidencia['telefono_contacto']) ?></p>
    <p><strong>Fecha:</strong> <?= e(date('d/m/Y H:i', strtotime($incidencia['fecha_servicio']))) ?></p>
    <p><strong>Horario:</strong> <?= e($incidencia['franja_horaria']) ?></p>
    <p><strong>Urgencia:</strong> <?= e($incidencia['tipo_urgencia']) ?></p>
    <p><strong>Estado:</strong> <?= e($incidencia['estado']) ?></p>

    <hr>

    <h2>Actualizar estado</h2>

    <?php if ($incidencia['estado'] === 'Asignada'): ?>

        <form method="POST" action="index.php?c=tecnicos&a=cambiarEstado">

            <input type="hidden" name="incidencia_id" value="<?= e($incidencia['id']) ?>">
            <input type="hidden" name="nuevo_estado" value="En curso">

            <label for="observacion">Observación (opcional):</label>
            <br>
            <textarea name="observacion" id="observacion" maxlength="255"></textarea>
            <br>

            <button type="submit">Comenzar reparación</button>

        </form>

    <?php elseif ($incidencia['estado'] === 'En curso'): ?>

        <form method="POST" action="index.php?c=tecnicos&a=cambiarEstado">

            <input type="hidden" name="incidencia_id" value="<?= e($incidencia['id']) ?>">
            <input type="hidden" name="nuevo_estado" value="Finalizada">

            <label for="observacion">Observación (opcional):</label>
            <br>
            <textarea name="observacion" id="observacion" maxlength="255"></textarea>
            <br>

            <button type="submit">Finalizar reparación</button>

        </form>

    <?php elseif ($incidencia['estado'] === 'Finalizada'): ?>

        <p>Esta reparación ya está finalizada.</p>

    <?php else: ?>

        <p>No puedes modificar esta reparación en su estado actual.</p>

    <?php endif; ?>

    <hr>

    <h2>Historial de estados</h2>

    <?php if (empty($historial)): ?>

        <p>No hay movimientos registrados.</p>

    <?php else: ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Estado anterior</th>
                    <th>Estado nuevo</th>
                    <th>Responsable</th>
                    <th>Fecha</th>
                    <th>Observación</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($historial as $movimiento): ?>
                    <tr>
                        <td><?= e($movimiento['estado_anterior'] ?? 'Sin estado anterior') ?></td>
                        <td><?= e($movimiento['estado_nuevo']) ?></td>
                        <td><?= e($movimiento['responsable']) ?></td>
                        <td><?= e(date('d/m/Y H:i', strtotime($movimiento['fecha_cambio']))) ?></td>
                        <td><?= e($movimiento['observacion'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>
