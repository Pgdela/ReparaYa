<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

require __DIR__ . '/../app/helpers/Auth.php';

function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

require __DIR__ . '/../app/models/Database.php';
require __DIR__ . '/../app/models/Usuario.php';
require __DIR__ . '/../app/models/Incidencia.php';
require __DIR__ . '/../app/models/Tecnico.php';

require __DIR__ . '/../app/controllers/AuthController.php';
require __DIR__ . '/../app/controllers/IncidenciaController.php';
require __DIR__ . '/../app/controllers/TecnicoController.php';

$accion = $_GET['a'] ?? 'login';
$controlador = $_GET['c'] ?? 'auth';

$permitidas = [
    'login',
    'registro',
    'inicio',
    'logout',
    'admin',
    'tecnico',
    'cliente',
    'perfil'
];

if ($controlador === 'auth' && in_array($accion, $permitidas, true)) {
    (new AuthController())->$accion();

} elseif ($controlador === 'incidencias' && $accion === 'misIncidencias') {
    (new IncidenciaController())->misIncidencias();

} elseif ($controlador === 'incidencias' && $accion === 'nueva') {
    (new IncidenciaController())->nueva();

} elseif ($controlador === 'incidencias' && $accion === 'detalle') {
    (new IncidenciaController())->detalle();

} elseif ($controlador === 'incidencias' && $accion === 'administrar') {
    (new IncidenciaController())->administrar();

} elseif ($controlador === 'incidencias' && $accion === 'detalleAdmin') {
    (new IncidenciaController())->detalleAdmin();

} elseif ($controlador === 'incidencias' && $accion === 'calendario') {
    (new IncidenciaController())->calendario();

} elseif ($controlador === 'incidencias' && $accion === 'asignar') {
    (new IncidenciaController())->asignar();

} elseif ($controlador === 'tecnicos' && $accion === 'administrar') {
    (new TecnicoController())->administrar();

} elseif ($controlador === 'tecnicos' && $accion === 'misIncidencias') {
    (new TecnicoController())->misIncidencias();

} elseif ($controlador === 'tecnicos' && $accion === 'detalle') {
    (new TecnicoController())->detalle();

} elseif ($controlador === 'tecnicos' && $accion === 'cambiarEstado') {
    (new TecnicoController())->cambiarEstado();

} elseif ($controlador === 'incidencias' && $accion === 'cancelar') {
    (new IncidenciaController())->cancelar();

} else {
    http_response_code(404);
    echo 'Página no encontrada';
}
