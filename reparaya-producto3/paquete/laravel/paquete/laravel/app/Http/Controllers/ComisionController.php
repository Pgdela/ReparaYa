<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use Illuminate\Support\Facades\Auth;

class ComisionController extends Controller
{
    private function comprobarAdministrador(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'admin',
            403,
            'No tienes permiso para consultar las comisiones.'
        );
    }

    public function index()
    {
        $this->comprobarAdministrador();

        $comisiones = Comision::with([
            'gestora',
            'servicio',
            'incidencia',
        ])
            ->orderByDesc('fecha_generacion')
            ->orderByDesc('created_at')
            ->paginate(15);

        $totalGenerado = Comision::whereNotNull('incidencia_id')
            ->whereNotNull('fecha_generacion')
            ->sum('importe_comision');

        $totalRegistros = Comision::count();

        return view('admin.comisiones.index', compact(
            'comisiones',
            'totalGenerado',
            'totalRegistros'
        ));
    }
}