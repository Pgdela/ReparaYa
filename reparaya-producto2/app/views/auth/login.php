<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Acceso - ReparaYa</title></head>
<body>
<h1>Iniciar sesión</h1>
<?php if (!empty($_SESSION['mensaje'])): ?>
  <p style="color:green"><?= e($_SESSION['mensaje']) ?></p>
  <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>
<?php foreach (($errores ?? []) as $err): ?>
  <p style="color:red"><?= e($err) ?></p>
<?php endforeach; ?>
<form method="post">
  <p><label>Correo <input name="email" type="email" value="<?= e($email ?? '') ?>"></label></p>
  <p><label>Contraseña <input name="password" type="password"></label></p>
  <p><button type="submit">Entrar</button></p>
</form>
<p><a href="index.php?c=auth&a=registro">Crear una cuenta</a></p>
</body>
</html>