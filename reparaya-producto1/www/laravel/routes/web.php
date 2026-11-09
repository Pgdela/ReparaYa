<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reparaya', function () {
    return 'ReparaYa - Grupo 5 ';
});