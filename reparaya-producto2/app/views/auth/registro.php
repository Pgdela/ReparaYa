<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Registro - ReparaYa</title></head>
<body>
<h1>Crear cuenta</h1>
<?php $datos = $datos ?? []; ?>
<?php $datos = $datos ?? []; ?>
<?php $errores = $errores ?? []; ?>
<?php foreach ($errores as $err): ?>
  <p style="color:red"><?= e($err) ?></p>
<?php endforeach; ?>
<form method="post">
  <p><label>Nombre <input name="nombre" value="<?= e($datos['nombre']) ?>"></label></p>
  <p><label>Correo <input name="email" type="email" value="<?= e($datos['email']) ?>"></label></p>
  <p><label>Teléfono <input name="telefono" value="<?= e($datos['telefono']) ?>"></label></p>
  <p><label>Contraseña <input name="password" type="password"></label></p>
  <p><label>Repite la contraseña <input name="password2" type="password"></label></p>
  <p><button type="submit">Registrarme</button></p>
</form>
<p><a href="index.php?c=auth&a=login">Ya tengo cuenta</a></p>
</body>
</html>