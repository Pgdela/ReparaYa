<?php
requiereCliente();

$incidencia = $incidencia ?? [];
$historial = $historial ?? [];

$puedeCancelar = false;

if (
    in_array(
        $incidencia['estado'] ?? '',
        ['Pendiente', 'Asignada'],
        true
    ) &&
    !empty($incidencia['fecha_servicio'])
) {
    $horasRestantes = strtotime($incidencia['fecha_servicio']) - time();

    $puedeCancelar = $horasRestantes > (48 * 3600);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la reparación - ReparaYa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
        }

        .contenedor {
            max-width: 850px;
            margin: auto;
        }

        .bloque {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .boton-cancelar {
            background: #c62828;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        a {
            color: #1769aa;
        }
    </style>
</head>

<body>

<div class="contenedor">

    <h1>Detalles de la reparación</h1>

    <?php if (!empty($_SESSION['mensaje'])): ?>
        <p><?= e($_SESSION['mensaje']) ?></p>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="bloque">

        <p>
            <strong>Localizador:</strong>
            <?= e($incidencia['localizador'] ?? '') ?>
        </p>

        <p>
            <strong>Tipo de reparación:</strong>
            <?= e($incidencia['nombre_especialidad'] ?? '') ?>
        </p>

        <p>
            <strong>Precio base:</strong>
            <?= e(number_format((float) ($incidencia['precio_base'] ?? 0), 2, ',', '.')) ?> €
        </p>

        <p>
            <strong>Descripción:</strong>
            <?= e($incidencia['descripcion'] ?? '') ?>
        </p>

        <p>
            <strong>Dirección:</strong>
            <?= e($incidencia['direccion'] ?? '') ?>
        </p>

        <p>
            <strong>Teléfono de contacto:</strong>
            <?= e($incidencia['telefono_contacto'] ?? '') ?>
        </p>

        <p>
            <strong>Fecha del servicio:</strong>
            <?= e(!empty($incidencia['fecha_servicio']) ? date('d/m/Y H:i', strtotime($incidencia['fecha_servicio'])) : '') ?>
        </p>

        <p>
            <strong>Franja horaria:</strong>
            <?= e($incidencia['franja_horaria'] ?? '') ?>
        </p>

        <p>
            <strong>Tipo de urgencia:</strong>
            <?= e($incidencia['tipo_urgencia'] ?? '') ?>
        </p>

        <p>
            <strong>Estado actual:</strong>
            <?= e($incidencia['estado'] ?? '') ?>
        </p>

        <?php if ($puedeCancelar): ?>

            <form
                method="POST"
                action="index.php?c=incidencias&a=cancelar"
                onsubmit="return confirm('¿Seguro que quieres cancelar esta reparación?');"
            >

                <input
                    type="hidden"
                    name="incidencia_id"
                    value="<?= e($incidencia['id'] ?? '') ?>"
                >

                <button type="submit" class="boton-cancelar">
                    Cancelar reparación
                </button>

            </form>

        <?php elseif (
            in_array(
                $incidencia['estado'] ?? '',
                ['Pendiente', 'Asignada'],
                true
            )
        ): ?>

            <p>
                Ya no puedes cancelar esta reparación porque faltan
                48 horas o menos para el servicio.
            </p>

        <?php endif; ?>

    </div>

    <div class="bloque">

        <h2>Historial de estados</h2>

        <?php if (empty($historial)): ?>

            <p>No hay movimientos registrados todavía.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>Estado anterior</th>
                        <th>Estado nuevo</th>
                        <th>Fecha</th>
                        <th>Responsable</th>
                        <th>Observación</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($historial as $movimiento): ?>

                        <tr>
                            <td>
                                <?= e($movimiento['estado_anterior'] ?? 'Sin estado anterior') ?>
                            </td>

                            <td>
                                <?= e($movimiento['estado_nuevo'] ?? '') ?>
                            </td>

                            <td>
                                <?= e(!empty($movimiento['fecha_cambio']) ? date('d/m/Y H:i', strtotime($movimiento['fecha_cambio'])) : '') ?>
                            </td>

                            <td>
                                <?= e($movimiento['responsable'] ?? '') ?>
                            </td>

                            <td>
                                <?= e($movimiento['observacion'] ?? '') ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

        <?php endif; ?>

    </div>

    <p>
        <a href="index.php?c=incidencias&a=misIncidencias">
            Volver a mis reparaciones
        </a>
    </p>

</div>

</body>
</html>
