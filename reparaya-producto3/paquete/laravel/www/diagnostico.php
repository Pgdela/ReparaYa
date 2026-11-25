<?php
$proyecto = 'ReparaYa';
$grupo    = 'Grupo 5';
$alumno   = 'Paula Gómez';

// En AWS, las credenciales están en un archivo fuera de public_html
$cfg = __DIR__ . '/../reparaya_config.php';
if (file_exists($cfg)) {
    $c = require $cfg;
    $entorno = 'AWS';
    $host = $c['host']; $name = $c['name'];
    $user = $c['user']; $pass = $c['pass'];
} else {
    $entorno = getenv('APP_ENV_NAME') ?: 'local';
    $host = getenv('DB_HOST') ?: 'db';
    $name = getenv('DB_NAME') ?: 'reparaya';
    $user = getenv('DB_USER') ?: 'reparaya_user';
    $pass = getenv('DB_PASSWORD') ?: '';
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 3,
    ]);
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    $db = "Conexión correcta (MySQL $version)";
    $ok = true;
} catch (PDOException $e) {
    $db = 'Error de conexión con la base de datos';
    $ok = false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Diagnóstico - <?= htmlspecialchars($proyecto) ?></title>
<style>
  body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; }
  td { padding: 6px 12px; border-bottom: 1px solid #ddd; }
  .ok { color: green; } .ko { color: red; }
</style>
</head>
<body>
<h1>Diagnóstico del entorno</h1>
<table>
  <tr><td><b>Proyecto</b></td><td><?= htmlspecialchars($proyecto) ?></td></tr>
  <tr><td><b>Grupo</b></td><td><?= htmlspecialchars($grupo) ?></td></tr>
  <tr><td><b>Estudiante</b></td><td><?= htmlspecialchars($alumno) ?></td></tr>
  <tr><td><b>Entorno</b></td><td><?= htmlspecialchars($entorno) ?></td></tr>
  <tr><td><b>Versión de PHP</b></td><td><?= PHP_VERSION ?></td></tr>
  <tr><td><b>Fecha y hora</b></td><td><?= date('d/m/Y H:i:s') ?></td></tr>
  <tr><td><b>Base de datos</b></td>
      <td class="<?= $ok ? 'ok' : 'ko' ?>"><?= htmlspecialchars($db) ?></td></tr>
</table>
</body>
</html>