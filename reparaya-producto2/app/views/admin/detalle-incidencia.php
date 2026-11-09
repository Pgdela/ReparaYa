<?php
requiereAdmin();

$incidencia = $incidencia ?? [];
$historial = $historial ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de reparación - ReparaYa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
        }

        .contenedor {
            max-width: 900px;
            margin: auto;
        }

        .bloque {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .dato {
            margin-bottom: 12px;
        }

        .urgente {
            color: #c62828;
            font-weight: bold;
        }

        .estandar {
            color: #2874c8;
            font-weight: bold;
        }

        .boton-cancelar {
            background: #c62828;
            color: white;
            border: 0;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

    <h1>Detalle de reparación</h1>

    <?php if (!empty($_SESSION['mensaje'])): ?>
        <p><?= e($_SESSION['mensaje']) ?></p>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <p>
        <a href="index.php?c=incidencias&a=calendario">Volver al calendario</a>
        |
        <a href="index.php?c=incidencias&a=administrar">Lista de reparaciones</a>
    </p>

    <div class="bloque">

        <h2>Información general</h2>

        <p class="dato">
            <strong>Localizador:</strong>
            <?= e($incidencia['localizador'] ?? '') ?>
        </p>

        <p class="dato">
            <strong>Estado:</strong>
            <?= e($incidencia['estado'] ?? '') ?>
        </p>

        <p class="dato">
            <strong>Urgencia:</strong>
            <span class="<?= (($incidencia['tipo_urgencia'] ?? '') === 'Urgente') ? 'urgente' : 'estandar' ?>">
                <?= e($incidencia['tipo_urgencia'] ?? '') ?>
            </span>
        </p>

        <p class="dato">
            <strong>Fecha del servicio:</strong>
            <?= e(!empty($incidencia['fecha_servicio']) ? date('d/m/Y H:i', strtotime($incidencia['fecha_servicio'])) : 'Sin fecha') ?>
        </p>

        <p class="dato">
            <strong>Franja horaria:</strong>
            <?= e($incidencia['franja_horaria'] ?? '') ?>
        </p>

        <p class="dato">
            <strong>Especialidad:</strong>
            <?= e($incidencia['nombre_especialidad'] ?? '') ?>
        </p>

        <p class="dato">
            <strong>Precio base:</strong>
            <?= e(number_format((float) ($incidencia['precio_base'] ?? 0), 2, ',', '.')) ?> €
        </p>

        <?php if (in_array($incidencia['estado'] ?? '', ['Pendiente', 'Asignada'], true)): ?>

            <form method="POST"
                  action="index.php?c=incidencias&a=cancelar"
                  onsubmit="return confirm('¿Seguro que quieres cancelar esta reparación?');">

                <input type="hidden"
                       name="incidencia_id"
                       value="<?= e($incidencia['id'] ?? '') ?>">

                <button type="submit" class="boton-cancelar">
                    Cancelar reparación
                </button>
            </form>

        <?php endif; ?>

    </div>

    <div class="bloque">

        <h2>Información del cliente</h2>

        <p class="dato">
            <strong>Nombre:</strong>
            <?= e($incidencia['nombre_cliente'] ?? '') ?>
        </p>

        <p class="dato">
            <strong>Correo:</strong>
            <?= e($incidencia['email_cliente'] ?? '') ?>
        </p>

        <p class="dato">
            <strong>Teléfono:</strong>
            <?= e($incidencia['telefono_contacto'] ?? '') ?>
        </p>

        <p class="dato">
            <strong>Dirección:</strong>
            <?= e($incidencia['direccion'] ?? '') ?>
        </p>

    </div>

    <div class="bloque">
        <h2>Descripción de la avería</h2>
        <p><?= nl2br(e($incidencia['descripcion'] ?? '')) ?></p>
    </div>

    <div class="bloque">
        <h2>Técnico asignado</h2>
        <p><?= e($incidencia['nombre_tecnico'] ?? 'Sin asignar') ?></p>
    </div>

    <div class="bloque">

        <h2>Historial de estados</h2>

        <?php if (empty($historial)): ?>

            <p>No hay movimientos registrados.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estado anterior</th>
                        <th>Estado nuevo</th>
                        <th>Responsable</th>
                        <th>Observación</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($historial as $movimiento): ?>
                        <tr>
                            <td>
                                <?= e(!empty($movimiento['fecha_cambio']) ? date('d/m/Y H:i', strtotime($movimiento['fecha_cambio'])) : 'Sin fecha') ?>
                            </td>
                            <td><?= e($movimiento['estado_anterior'] ?? 'Estado inicial') ?></td>
                            <td><?= e($movimiento['estado_nuevo'] ?? 'Sin estado') ?></td>
                            <td><?= e($movimiento['responsable'] ?? 'Sin responsable') ?></td>
                            <td><?= e($movimiento['observacion'] ?? 'Sin observaciones') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
