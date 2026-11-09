<?php
requiereCliente();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del cliente - ReparaYa</title>
</head>
<body>

    <h1>Panel del cliente</h1>

    <p>Bienvenido, <?= e($_SESSION['nombre']) ?></p>

    <p>Desde aquí podrás solicitar reparaciones y consultar tus avisos.</p>

    <h2>Mis opciones</h2>

    <p>
        <a href="index.php?c=incidencias&a=nueva">
            Solicitar una reparación
        </a>
    </p>

    <p>
        <a href="index.php?c=incidencias&a=misIncidencias">
            Mis reparaciones
        </a>
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

</body>
</html>
