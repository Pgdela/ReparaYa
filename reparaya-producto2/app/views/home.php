<?php
requiereLogin();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ReparaYa</title>
</head>
<body>

    <h1>Bienvenido a ReparaYa, <?= e($_SESSION['nombre']) ?></h1>

    <p>Gestión de reparaciones domésticas.</p>

    <p>Solicita una reparación de forma rápida y sencilla.</p>

    <p>
        <a href="index.php?c=auth&a=logout">Cerrar sesión</a>
    </p>

</body>
</html>
