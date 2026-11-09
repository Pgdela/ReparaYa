<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Inicio - ReparaYa</title></head>
<body>
<h1>Hola, <?= e($_SESSION['usuario']['nombre']) ?></h1>
<p>Tu rol: <?= e($_SESSION['usuario']['rol']) ?></p>
<p><a href="index.php?c=auth&a=logout">Cerrar sesión</a></p>
</body>
</html>