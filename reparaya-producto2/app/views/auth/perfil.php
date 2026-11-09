<?php
requiereLogin();

if (!isset($usuario) || !is_array($usuario)) {
    $usuario = ['nombre' => '', 'email' => ''];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi perfil - ReparaYa</title>
</head>
<body>

    <h1>Mi perfil</h1>

    <?php if (!empty($_SESSION['mensaje'])): ?>
        <p style="color: green;">
            <?= e($_SESSION['mensaje']) ?>
        </p>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <?php foreach ($errores as $error): ?>
            <p style="color: red;"><?= e($error) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST">

        <p>
            <label>Nombre:</label><br>
            <input
                type="text"
                name="nombre"
                value="<?= e($usuario['nombre']) ?>"
                required
            >
        </p>

        <p>
            <label>Correo electrónico:</label><br>
            <input
                type="email"
                name="email"
                value="<?= e($usuario['email']) ?>"
                required
            >
        </p>

        <h3>Cambiar contraseña</h3>
        <p>Déjala vacía si no quieres cambiarla.</p>

        <p>
            <label>Nueva contraseña:</label><br>
            <input type="password" name="password">
        </p>

        <p>
            <label>Repetir nueva contraseña:</label><br>
            <input type="password" name="password2">
        </p>

        <button type="submit">Guardar cambios</button>

    </form>

    <p>
        <a href="index.php?c=auth&a=inicio">Volver</a>
    </p>

</body>
</html>
