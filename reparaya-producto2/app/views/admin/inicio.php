<?php
requiereAdmin();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de administración - ReparaYa</title>
</head>
<body>

    <h1>Panel de administración</h1>

    <p>
        Bienvenido, <?= e($_SESSION['nombre']) ?>
    </p>

    <p>
        Desde aquí podrás gestionar las reparaciones,
        los técnicos y los usuarios de ReparaYa.
    </p>

    <h2>Gestión de ReparaYa</h2>

    <p>
        <a href="index.php?c=incidencias&a=administrar">
            Gestionar reparaciones
        </a>
    </p>

    <p>
        <a href="index.php?c=incidencias&a=calendario">
            Consultar calendario de reparaciones
        </a>
    </p>

    <p>
        <a href="index.php?c=tecnicos&a=administrar">
            Gestionar técnicos
        </a>
    </p>

    <h2>Cuenta</h2>

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

</body>
</html>
