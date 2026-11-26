<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GestoraController;
use App\Http\Controllers\GestoraPanelController;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\GestoraComisionController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\ComunidadController;
use App\Http\Controllers\GestoraComunidadController;
use App\Http\Controllers\GestoraIncidenciaController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.usuarios.index');
    }

    if (Auth::user()->role === 'gestora') {
        return redirect()->route('gestora.panel');
    }

    return redirect()->route('reparaya');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])
    ->middleware('guest')->name('register');
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UserController::class)
        ->except(['show'])->parameters(['usuarios' => 'user']);

    Route::resource('gestoras', GestoraController::class)
        ->except(['show']);

    Route::resource('zonas', ZonaController::class)
        ->except(['show']);

    Route::resource('comunidades', ComunidadController::class)
        ->except(['show']);

    // Consulta de comisiones: sin creacion manual.
    Route::get('/comisiones', [ComisionController::class, 'index'])
        ->name('comisiones.index');
});

Route::middleware('auth')->prefix('gestora')->name('gestora.')->group(function () {
    Route::get('/', [GestoraPanelController::class, 'index'])
        ->name('panel');

    // Gestion de comunidades
    Route::get('/comunidades', [GestoraComunidadController::class, 'index'])
        ->name('comunidades.index');

    Route::get('/comunidades/crear', [GestoraComunidadController::class, 'create'])
        ->name('comunidades.create');

    Route::post('/comunidades', [GestoraComunidadController::class, 'store'])
        ->name('comunidades.store');

    Route::get('/comunidades/{id}/editar', [GestoraComunidadController::class, 'edit'])
        ->name('comunidades.edit');

    Route::put('/comunidades/{id}', [GestoraComunidadController::class, 'update'])
        ->name('comunidades.update');

    Route::delete('/comunidades/{id}', [GestoraComunidadController::class, 'destroy'])
        ->name('comunidades.destroy');

    // Gestion de incidencias
    Route::get('/incidencias', [GestoraIncidenciaController::class, 'index'])
        ->name('incidencias.index');

    Route::get('/incidencias/crear', [GestoraIncidenciaController::class, 'create'])
        ->name('incidencias.create');

    Route::post('/incidencias', [GestoraIncidenciaController::class, 'store'])
        ->name('incidencias.store');

    // Comisiones y liquidaciones de la gestora
    Route::get('/comisiones', [GestoraComisionController::class, 'index'])
        ->name('comisiones.index');

    Route::get('/liquidaciones', [GestoraComisionController::class, 'liquidaciones'])
        ->name('liquidaciones');
});

Route::middleware('auth')->group(function () {
    Route::get('/reparaya', function () {
        return view('reparaya.index');
    })->name('reparaya');

    Route::get('/reparaya/solicitudes', [SolicitudController::class, 'index'])
        ->name('reparaya.solicitudes.index');

    Route::get('/reparaya/solicitudes/crear', [SolicitudController::class, 'create'])
        ->name('reparaya.solicitudes.create');

    Route::post('/reparaya/solicitudes', [SolicitudController::class, 'store'])
        ->name('reparaya.solicitudes.store');

    Route::get('/reparaya/perfil', [PerfilController::class, 'edit'])
        ->name('reparaya.perfil');

    Route::put('/reparaya/perfil', [PerfilController::class, 'update'])
        ->name('reparaya.perfil.update');

    Route::patch('/incidencias/{incidencia}/estado', [IncidenciaController::class, 'actualizarEstado'])
        ->name('incidencias.estado');
});
