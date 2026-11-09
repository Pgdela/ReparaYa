<?php
requiereAdmin();

$vista = $_GET['vista'] ?? 'mes';
$fechaSeleccionada = $fechaSeleccionada ?? new DateTime();

$mesActual = (int) $fechaSeleccionada->format('n');
$anioActual = (int) $fechaSeleccionada->format('Y');

$primerDiaMes = new DateTime(
    $fechaSeleccionada->format('Y-m-01')
);

$diaSemana = (int) $primerDiaMes->format('N');
$inicioCalendario = clone $primerDiaMes;
$inicioCalendario->modify('-' . ($diaSemana - 1) . ' days');

$numeroDias = (int) $primerDiaMes->format('t');

$mesAnterior = clone $fechaSeleccionada;
$mesAnterior->modify('-1 month');

$mesSiguiente = clone $fechaSeleccionada;
$mesSiguiente->modify('+1 month');

$diaAnterior = clone $fechaSeleccionada;
$diaAnterior->modify('-1 day');

$diaSiguiente = clone $fechaSeleccionada;
$diaSiguiente->modify('+1 day');

$incidenciasPorDia = [];
$incidencias = $incidencias ?? [];

foreach ($incidencias as $incidencia) {
    $dia = date('Y-m-d', strtotime($incidencia['fecha_servicio']));
    $incidenciasPorDia[$dia][] = $incidencia;
}

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
    4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
    10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

$diasSemana = [
    'Lunes', 'Martes', 'Miércoles',
    'Jueves', 'Viernes', 'Sábado', 'Domingo'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario - ReparaYa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 25px;
        }

        .navegacion {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .navegacion a,
        .vistas a {
            padding: 9px 14px;
            background: #eeeeee;
            color: #222222;
            text-decoration: none;
            border-radius: 5px;
        }

        .vistas {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .calendario {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .calendario th,
        .calendario td {
            border: 1px solid #cccccc;
            padding: 8px;
            vertical-align: top;
            height: 105px;
        }

        .calendario th {
            background: #eeeeee;
        }

        .dia-numero {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .evento {
            display: block;
            padding: 6px;
            margin-bottom: 5px;
            border-radius: 4px;
            color: #222222;
            font-size: 12px;
            text-decoration: none;
        }

        .urgente {
            background: #ffcaca;
            border-left: 4px solid #c62828;
        }

        .estandar {
            background: #d7e8ff;
            border-left: 4px solid #2874c8;
        }

        .fuera-mes {
            background: #f7f7f7;
            color: #999999;
        }

        .leyenda {
            margin: 20px 0;
        }

        .leyenda span {
            display: inline-block;
            padding: 7px 12px;
            margin-right: 10px;
        }

        @media (max-width: 700px) {
            .calendario th,
            .calendario td {
                padding: 3px;
                font-size: 11px;
                height: 80px;
            }

            .evento {
                font-size: 10px;
                padding: 3px;
            }
        }
    </style>
</head>

<body>

    <h1>Calendario de reparaciones</h1>

    <p>
        <a href="index.php?c=auth&a=admin">
            Volver al panel de administración
        </a>
    </p>

    <div class="vistas">
        <a href="index.php?c=incidencias&a=calendario&vista=mes&fecha=<?= e($fechaSeleccionada->format('Y-m-d')) ?>">
            Mes
        </a>

        <a href="index.php?c=incidencias&a=calendario&vista=semana&fecha=<?= e($fechaSeleccionada->format('Y-m-d')) ?>">
            Semana
        </a>

        <a href="index.php?c=incidencias&a=calendario&vista=dia&fecha=<?= e($fechaSeleccionada->format('Y-m-d')) ?>">
            Día
        </a>
    </div>

    <div class="navegacion">

        <?php if ($vista === 'mes'): ?>

            <a href="index.php?c=incidencias&a=calendario&vista=mes&fecha=<?= e($mesAnterior->format('Y-m-d')) ?>">
                Mes anterior
            </a>

            <h2><?= e($meses[$mesActual] . ' ' . $anioActual) ?></h2>

            <a href="index.php?c=incidencias&a=calendario&vista=mes&fecha=<?= e($mesSiguiente->format('Y-m-d')) ?>">
                Mes siguiente
            </a>

        <?php else: ?>

            <a href="index.php?c=incidencias&a=calendario&vista=<?= e($vista) ?>&fecha=<?= e($diaAnterior->format('Y-m-d')) ?>">
                Anterior
            </a>

            <h2><?= e($fechaSeleccionada->format('d/m/Y')) ?></h2>

            <a href="index.php?c=incidencias&a=calendario&vista=<?= e($vista) ?>&fecha=<?= e($diaSiguiente->format('Y-m-d')) ?>">
                Siguiente
            </a>

        <?php endif; ?>

    </div>

    <div class="leyenda">
        <span class="urgente">Urgente</span>
        <span class="estandar">Estándar</span>
    </div>

    <?php if ($vista === 'mes'): ?>

        <table class="calendario">
            <thead>
                <tr>
                    <?php foreach ($diasSemana as $nombreDia): ?>
                        <th><?= e($nombreDia) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php
                $diaIteracion = clone $inicioCalendario;
                $totalCeldas = (int) ceil(($diaSemana - 1 + $numeroDias) / 7) * 7;
                ?>

                <?php for ($i = 0; $i < $totalCeldas; $i++): ?>

                    <?php if ($i % 7 === 0): ?>
                        <tr>
                    <?php endif; ?>

                    <?php
                    $fechaDia = $diaIteracion->format('Y-m-d');
                    $esOtroMes = (int) $diaIteracion->format('n') !== $mesActual;
                    ?>

                    <td class="<?= $esOtroMes ? 'fuera-mes' : '' ?>">

                        <div class="dia-numero">
                            <?= e($diaIteracion->format('j')) ?>
                        </div>

                        <?php foreach ($incidenciasPorDia[$fechaDia] ?? [] as $incidencia): ?>

                            <a
                                class="evento <?= $incidencia['tipo_urgencia'] === 'Urgente' ? 'urgente' : 'estandar' ?>"
                                href="index.php?c=incidencias&a=detalleAdmin&id=<?= e($incidencia['id']) ?>"
                            >
                                <strong><?= e($incidencia['localizador']) ?></strong>
                                <br>
                                <?= e($incidencia['franja_horaria']) ?>
                                <br>
                                <?= e($incidencia['estado']) ?>
                            </a>

                        <?php endforeach; ?>

                    </td>

                    <?php
                    $diaIteracion->modify('+1 day');
                    ?>

                    <?php if ($i % 7 === 6): ?>
                        </tr>
                    <?php endif; ?>

                <?php endfor; ?>
            </tbody>
        </table>

    <?php else: ?>

        <?php
        $fechaInicio = clone $fechaSeleccionada;

        if ($vista === 'semana') {
            $fechaInicio->modify('-' . ((int) $fechaInicio->format('N') - 1) . ' days');
            $numeroDiasVista = 7;
        } else {
            $numeroDiasVista = 1;
        }
        ?>

        <?php for ($i = 0; $i < $numeroDiasVista; $i++): ?>

            <?php
            $fechaDia = $fechaInicio->format('Y-m-d');
            ?>

            <h3><?= e($diasSemana[(int) $fechaInicio->format('N') - 1]) ?> <?= e($fechaInicio->format('d/m/Y')) ?></h3>

            <?php if (empty($incidenciasPorDia[$fechaDia])): ?>

                <p>No hay reparaciones programadas.</p>

            <?php else: ?>

                <?php foreach ($incidenciasPorDia[$fechaDia] as $incidencia): ?>

                    <a
    class="evento <?= $incidencia['tipo_urgencia'] === 'Urgente' ? 'urgente' : 'estandar' ?>"
    href="index.php?c=incidencias&a=detalleAdmin&id=<?= e($incidencia['id']) ?>"
    style="text-decoration: none; color: inherit;"
>

                        <strong><?= e($incidencia['localizador']) ?></strong>

                        <p>Horario: <?= e($incidencia['franja_horaria']) ?></p>
                        <p>Cliente: <?= e($incidencia['nombre_cliente']) ?></p>
                        <p>Especialidad: <?= e($incidencia['nombre_especialidad']) ?></p>
                        <p>Técnico: <?= e($incidencia['nombre_tecnico'] ?? 'Sin asignar') ?></p>
                        <p>Estado: <?= e($incidencia['estado']) ?></p>

                    </a>

                <?php endforeach; ?>

            <?php endif; ?>

            <?php $fechaInicio->modify('+1 day'); ?>

        <?php endfor; ?>

    <?php endif; ?>

</body>
</html>
