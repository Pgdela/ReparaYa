<?php

function requiereLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: index.php?c=auth&a=login');
        exit;
    }
}

function requiereRol(array $rolesPermitidos): void
{
    requiereLogin();

    if (!in_array($_SESSION['rol'], $rolesPermitidos, true)) {
        http_response_code(403);
        echo 'No tienes permiso para acceder a esta página.';
        exit;
    }
}


function requiereAdmin(): void
{
    requiereRol(['admin']);
}

function requiereTecnico(): void
{
    requiereRol(['tecnico']);
}

function requiereCliente(): void
{
    requiereRol(['particular']);
}