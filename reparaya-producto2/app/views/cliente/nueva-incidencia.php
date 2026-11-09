<?php
requiereCliente();
$datos = array_merge([
    'especialidad_id' => '',
    'descripcion' => '',
    'direccion' => '',
    'telefono_contacto' => '',
    'fecha_servicio' => '',
    'franja_horaria' => '',
    'tipo_urgencia' => 'Estándar',
], isset($datos) && is_array($datos) ? $datos : []);
$especialidades = isset($especialidades) && is_array($especialidades) ? $especialidades : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar reparación - ReparaYa</title>
</head>
<body>

    <h1>Solicitar una reparación</h1>

    <?php if (!empty($errores)): ?>
        <div style="color: red;">
            <h3>Revisa los siguientes errores:</h3>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <p>
        Las solicitudes estándar deben realizarse con al menos
        48 horas de antelación. Las urgentes pueden solicitarse
        con menos tiempo.
    </p>

    <form method="POST" action="index.php?c=incidencias&a=nueva">

        <label>Tipo de reparación:</label>
        <select name="especialidad_id" required>
            <option value="">Selecciona una especialidad</option>

            <?php foreach ($especialidades as $especialidad): ?>
                <option
                    value="<?= e($especialidad['id']) ?>"
                    <?= ($datos['especialidad_id'] == $especialidad['id']) ? 'selected' : '' ?>
                >
                    <?= e($especialidad['nombre_especialidad']) ?>
                    (<?= e($especialidad['precio_base']) ?> €)
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <label>Descripción de la avería:</label>
        <br>
        <textarea name="descripcion" required><?= e($datos['descripcion']) ?></textarea>

        <br><br>

        <label>Dirección:</label>
        <input
            type="text"
            name="direccion"
            maxlength="255"
            value="<?= e($datos['direccion']) ?>"
            required
        >

        <br><br>

        <label>Teléfono de contacto:</label>
        <input
            type="tel"
            name="telefono_contacto"
            value="<?= e($datos['telefono_contacto']) ?>"
            required
        >

        <br><br>

        <label>Fecha del servicio:</label>
        <input
            type="date"
            name="fecha_servicio"
            value="<?= e($datos['fecha_servicio']) ?>"
            min="<?= date('Y-m-d') ?>"
            required
        >

        <br><br>

        <label>Franja horaria:</label>
        <select name="franja_horaria" required>
            <option value="">Selecciona una franja</option>

            <option value="Mañana"
                <?= $datos['franja_horaria'] === 'Mañana' ? 'selected' : '' ?>>
                Mañana
            </option>

            <option value="Tarde"
                <?= $datos['franja_horaria'] === 'Tarde' ? 'selected' : '' ?>>
                Tarde
            </option>
        </select>

        <br><br>

        <label>Tipo de urgencia:</label>
        <select name="tipo_urgencia" required>

            <option value="Estándar"
                <?= $datos['tipo_urgencia'] === 'Estándar' ? 'selected' : '' ?>>
                Estándar
            </option>

            <option value="Urgente"
                <?= $datos['tipo_urgencia'] === 'Urgente' ? 'selected' : '' ?>>
                Urgente
            </option>

        </select>

        <br><br>

        <button type="submit">Enviar solicitud</button>

    </form>

    <p>
        <a href="index.php?c=auth&a=cliente">Volver al panel</a>
    </p>

</body>
</html>
